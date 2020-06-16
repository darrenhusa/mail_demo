Vue.component('modal-card', {

  template: `
  <div class="modal is-active">
    <div class="modal-background"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">
        <slot name="header"></slot>
        </p>
        <button class="delete" aria-label="close" @click="$emit('close')"></button>
      </header>
      <section class="modal-card-body">
        <slot></slot>
      </section>
      <footer class="modal-card-foot">
        <button class="button is-success" @click="$emit('close')">Close</button>
      </footer>
    </div>
  </div>
`

});

new Vue({
  el: "#root",

  data: {
    showModal: false
  }

});
