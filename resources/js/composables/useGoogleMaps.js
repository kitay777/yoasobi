// resources/js/composables/useGoogleMaps.js
let loading
export function loadGoogleMaps() {
  if (loading) return loading
  loading = new Promise((resolve) => {
    if (window.google?.maps) return resolve(window.google.maps)
    const s = document.createElement('script')
    s.src = `https://maps.googleapis.com/maps/api/js?key=${import.meta.env.VITE_GOOGLE_MAPS_API_KEY}&libraries=marker`
    s.async = true
    s.onload = () => resolve(window.google.maps)
    document.head.appendChild(s)
  })
  return loading
}
