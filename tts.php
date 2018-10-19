<script>
    var msg = new SpeechSynthesisUtterance('<?php echo $_GET['text']; ?>');
    window.speechSynthesis.speak(msg);
</script>