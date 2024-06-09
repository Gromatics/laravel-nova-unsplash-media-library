import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-laravel-nova-unsplash-media-library', IndexField)
  app.component('detail-laravel-nova-unsplash-media-library', DetailField)
  app.component('form-laravel-nova-unsplash-media-library', FormField)
})
