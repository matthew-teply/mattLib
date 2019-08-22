<script src='<?= WEB_SERVER . SRC_JS ?>jq.js'></script>
<script src='<?= WEB_SERVER . SRC_JS ?>ml.js'></script>
<script>
    // Create an instance of mattLib class, for global use
    const ml = new mattLib('<?= WEB_SERVER ?>', '<?= $_GET['app'] ?>/');
</script>
<script src='<?= WEB_SERVER . SRC_JS ?>ml_ajaxForm.js'></script>

<!-- Importing Vue.js -->
<script src="<?= WEB_SERVER . SRC_JS ?>vue.js"></script>