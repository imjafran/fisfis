const FisFIs = "fisfis"
const assets = [
  "/dashboard", 
  "/public/css/app.min.css", 
  "/public/js/app.min.js", 
  "/public/images/logo.png", 
]

self.addEventListener("install", installEvent => {
  installEvent.waitUntil(
    caches.open(FisFIs).then(cache => {
      cache.addAll(assets)
    })
  )
})

self.addEventListener("fetch", fetchEvent => {
    fetchEvent.respondWith(
      caches.match(fetchEvent.request).then(res => {
        return res || fetch(fetchEvent.request) || false
      })
    )
  })