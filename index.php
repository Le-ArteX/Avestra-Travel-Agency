<?php
// Root entry point – redirect visitors to the public-facing homepage.
// Admin/views/loader.php is the public home (the "Admin" folder holds all
// server-side code; the real admin dashboard requires authentication).
header('Location: Admin/views/loader.php');
exit;
