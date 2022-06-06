<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Micro Blog</title>
<link href="{{asset('/favicon.ico')}}" rel="shortcut icon" type="image/x-icon">
<link rel="stylesheet" href="{{ mix('/css/app.css') }}">
<script>
@if (Auth::check()) window.pp_user = <?php echo json_encode($user); ?>; @else window.pp_user = null; @endif
</script>
</head>
<body>
	<div id="app">
		<app-header></app-header>
		<div class="main"><router-view></router-view></div>
		<app-footer></app-footer>
	</div>
	<script src="{{ mix('/js/app.js') }}"></script>
</body>
</html>
