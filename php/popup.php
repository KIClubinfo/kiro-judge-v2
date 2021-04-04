<?php
// Permet de faire des jolies pop up pour les messages.

function popup($message, $timer=6000, $icon='success') {
    echo '<script>
const Toast = Swal.mixin({
  toast: true,
  position: "bottom-start",
  showConfirmButton: false,
  timer: '.$timer.',
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener("mouseenter", Swal.stopTimer)
    toast.addEventListener("mouseleave", Swal.resumeTimer)
  }
})

Toast.fire({
  icon: "'.$icon.'",
  title: "'.$message.'"
})
</script>';
   }

?>
