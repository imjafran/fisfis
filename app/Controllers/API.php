<?php

namespace App\Controllers;

include APPPATH . '/Libraries/Response.php';
include APPPATH . '/Libraries/Options.php';

class API extends BaseController
{
    use \Response;
    use \Options;

    protected $user = null;

    function __construct()
    {
        $this->db = db_connect();
        $this->authenticate();
    }

    public function authenticate()
    {
        $token = $this->get_bearer_token();

        $user = $this->db->table('users')->getWhere(['access_token' => $token])->getRow();
        if ($user) {
            $this->user = $user;
        }
    }

    public function index()
    {
        $this->send(['success' => false, 'message' => 'Not Allowed']);
    }


    public function get_questions()
    {
        $this->middleware_user();

        $questions = $this->db->table('questions')->orderBy('questions.created_at', 'DESC')->select('questions.*, (SELECT COUNT(messages.id) FROM messages WHERE messages.question_id = questions.id AND messages.deleted_at IS NULL) as messages')->getWhere(['questions.user_id' => $this->user->id, 'questions.deleted_at' => null])->getResult();

        $this->send_success($questions);
    }

    public function get_question()
    {
        $id = $this->request->getVar('id');

        $templates = $this->get_option('question_templates');

        $question = $this->db->table('questions')->getWhere(['id' => $id, 'deleted_at' => null])->getRow();
        if ($question) {
            $question->templates = $templates;
            $this->send_success($question);
        } else {
            $this->send_error([
                'templates' => $templates
            ]);
        }
    }

    public function update_question()
    {
        $this->middleware_user();

        $id = $this->request->getVar('id');
        $text = $this->request->getVar('text');
        $enabled = $this->request->getVar('enabled') ?? 1;

        $updated = false;

        $user_id = 1;

        $found = $this->db->table('questions')->getWhere(['id' => $id])->getRow();
        if ($found) {
            $updated = $this->db->table('questions')->set(['enabled' => $enabled])->where(['id' => $id])->update();
        } elseif (!empty($text)) {
            $slug = bin2hex(random_bytes(4));
            $updated = $this->db->table('questions')->insert(['text' => $text, 'enabled' => $enabled, 'user_id' => $user_id, 'slug' => $slug]);
            $id = $this->db->insertID();
        }

        if ($updated) {
            $this->send_success($id);
        } else {
            $this->send_error();
        }
    }

    public function delete_question()
    {
        $id = $this->request->getVar('id');
 
        $found = $this->db->table('questions')->getWhere(['id' => $id])->getRow();
        if ($found) {
            $updated = $this->db->table('questions')->set(['deleted_at' => date('Y-m-d H:i:s')])->where(['id' => $id, 'user_id' => $this->user->id])->update();
            if ($updated) {
                $this->send_success();
            } else {
                $this->send_error('not allowed');
            }
        } else {
            $this->send_error('question not found');
        }
    }

    public function get_profile()
    {
        $this->middleware_user();

        $user = $this->user;

        if(!$user->enabled){
            $this->send_error("Your account is disabled");
        }

        unset($user->fb_access_token);
        unset($user->access_token);
        $this->send_success($user);
    }

    public function update_profile()
    {
        $this->send(['success' => true]);
    }

    public function get_messages()
    {
        $this->middleware_user();
        $question_id = $this->request->getVar('id');

        if (!$question_id) {
            $this->send_error('Invalid Question ID');
        }

        $question = $this->db->table('questions')->getWhere(['id' => $question_id])->getRow();

        if (!$question || (!$this->is_admin() && $question->user_id != $this->user->id)) {
            $this->send_error('Invalid Question');
        }

        $messages =  $this->db->table('messages')->orderBy('created_at', 'DESC')->getWhere(['question_id' => $question_id, 'deleted_at' => null])->getResult();

        $this->send_success([
            'question' => $question,
            'messages' => $messages,
        ]);
    }

    public function create_message()
    {
        $message = $this->request->getVar('message');
        $question_id = $this->request->getVar('question_id');
        $csrf = $this->request->getVar('csrf');
        $user_info = $this->request->getVar('user_info');

        if(empty($message)) $this->send_error('Invalid message text'); 
        if(empty($question_id) || $question_id < 1) $this->send_error('Invalid question'); 

        $question = $this->db->table('questions')->getWhere(['id' => $question_id])->getRow();

        if(!$question) $this->send_error('Invalid question'); 
        if(!$question->enabled) $this->send_error('New messages for this question is disabled');


        # Create new message 
        $slug = bin2hex(random_bytes(4));

        $created = $this->db->table('messages')->insert([
            'slug' => $slug,
            'question_id' => $question_id,
            'text' => $message,
            'user_info' => json_encode($user_info),
        ]);

        if($created) $this->send_success();
        
        $this->send_error('Couldn\'t post message for this question');

    }

    public function delete_message()
    {
        $this->middleware_user();

        $id = $this->request->getVar('id');

        $message = $this->db->table('messages')->getWhere(['id' => $id])->getRow();
        if(!$message) $this->send_error('Invalid message' . $id);

        $question = $this->db->table('questions')->getWhere(['id' => $message->question_id])->getRow();
        if(!$question) $this->send_error('No question found');
        if(!$question->user_id != $this->user->id && !$this->is_admin()) $this->send_error('Permission denied');


        $deleted = $this->db->table('messages')->set(['deleted_at' => date('Y-m-d H:i:s')])->where(['id' => $id])->update();
        if($deleted) $this->send_success();
        $this->send_error('Couldn\'t delete the message');
    }

    public function update_options()
    {
        $this->middleware_admin();
        
        $templates = $this->get_option('question_templates', [
            'Am I cute?',
            'Should I try for media?',
            'Send me whatever you want',
        ]);
        $this->send(['success' => $templates]);
    }
    public function get_users()
    {
        $this->middleware_admin();

        $users = $this->db->table('users')->orderBy('created_at', 'DESC')->select('users.*, COUNT(questions.id) as questions')->join('questions', 'questions.user_id = users.id', 'left')->getWhere()->getResult();
        $this->send_success($users);
    }

    public function auth_user()
    {
        $fb_id = $this->request->getVar('fb_id');
        $fb_access_token = $this->request->getVar('fb_access_token');
        $name = $this->request->getVar('name');
        $email = $this->request->getVar('email');

        // $this->send([$fb_id, $fb_access_token, $name, $email]);
        // $profile_picture = $this->request->getVar('profile_picture');

        $user = $this->db->table('users')->getWhere(['fb_id' => $fb_id])->getRow();

        $access_token = bin2hex(random_bytes(4));

        $updated = false;

        if($user){
            $updated = $this->db->table('users')->set(['fb_access_token' => $fb_access_token, 'access_token' => $access_token])->update();
        } else {
            $updated = $this->db->table('users')->insert(
                [
                    'name' => $name,
                    'fb_id' => $fb_id,
                    'fb_access_token' => $fb_access_token,
                    'email' => $email,
                    // 'profile_picture' => $profile_picture,
                    'access_token' => $access_token
                ]
            );
        } 

        if($updated) {
            $this->send_success($access_token);
        }

        $this->send_error('');
 
    }
 
}
