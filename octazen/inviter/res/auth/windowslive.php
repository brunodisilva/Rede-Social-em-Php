<?php
/********************************************************************************
Unified Inviter Component
Windows Live Login callback

You may not reprint or redistribute this code without permission from Octazen Solutions.

Copyright 2009 Octazen Solutions. All Rights Reserved.
WWW: http://www.octazen.com
********************************************************************************/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<body>
<script type="text/javascript">
var authstr = '<?php echo urldecode($_REQUEST['ConsentToken']) ?>';
opener.ozAuthCallback('wa_hotmail',authstr);
window.close();
</script>
</body>
</html>
