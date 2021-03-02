<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">

	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ config('app.name') }} | Dashboard</title>

	<link rel="shortcut icon" href="{{ asset('/public/img/favicon/favicon.ico') }}" type="image/x-icon" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
	@yield('before-styles')

	{{ Html::style('public/admin/css/bootstrap.min.css') }}
	{{ Html::style('public/admin/css/select2.min.css') }}
	{{ Html::style('public/admin/css/font-awesome.min.css') }}
	{{ Html::style('public/admin/css/ionicons.min.css') }}
	{{ Html::style('public/admin/css/dataTables.bootstrap.min.css') }}
	{{ Html::style('public/admin/css/AdminLTE.min.css') }}
	{{ Html::style('public/admin/css/blue.css') }}
	{{ Html::style('public/admin/css/_all-skins.min.css') }}
	{{ Html::style('public/admin/css/custom.css') }}
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css?v=3.5.7" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css?v=2.6.2">
	@yield('after-styles')

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- Google Font -->
<link rel="stylesheet"
href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
	<style>#loading {color: #666666;
    position: fixed;
    height: 100%;
    width: 100%;
    z-index: 99999;
    top: 0;
    left: 0;
    float: left;
    text-align: center;
    padding-top: 25%;}
</style>
<div id="loading" style="display: none">
	<img src="{{ URL::asset('public/front/images/loading-profile.gif') }}" style=" z-index: +1;" width="150" height="150" alt="loader" />
</div>
<div class="wrapper">
	@include('admin.include.header')
	@include('admin.include.sidebar')
	<!-- Main content -->
	@yield('content')

	<footer class="main-footer">
		<div class="pull-right hidden-xs">
			{{-- <b>Version</b> 2.4.0 --}}
		</div>
		<strong>Copyright &copy; {{ date('Y') }} <a href="{{ route('admin.home') }}">{{ config('app.name') }}</a>.</strong> All rights
		reserved.
	</footer>

</div>

<!-- JavaScripts -->
@yield('before-scripts')
{{ Html::script('public/admin/js/jquery.min.js') }}
{{ Html::script('public/admin/js/bootstrap.min.js') }}
{{-- {{ Html::script('public/admin/js/Chart.js') }} --}}
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

{{ Html::script('public/admin/js/select2.full.min.js') }}
{{ Html::script('public/admin/js/jquery.dataTables.min.js') }}
{{ Html::script('public/admin/js/dataTables.bootstrap.min.js') }}
{{ Html::script('public/admin/js/custom.js') }}
{{ Html::script('public/admin/js/icheck.min.js') }}
{{ Html::script('public/admin/js/fastclick.js') }}
{{ Html::script('public/admin/js/adminlte.min.js') }}
{{ Html::script('public/admin/js/jquery.sparkline.min.js') }}
{{ Html::script('public/admin/js/jquery-jvectormap-1.2.2.min.js') }}
{{ Html::script('public/admin/js/jquery-jvectormap-world-mill-en.js') }}
{{ Html::script('public/admin/js/jquery.slimscroll.min.js') }}
{{ Html::script('public/admin/js/dashboard2.js') }}
{{ Html::script('public/admin/js/demo.js') }}
{{ Html::script('public/admin/js/treeview.js') }}
{{ Html::script('https://cloud.tinymce.com/stable/tinymce.min.js') }}
{{ Html::script('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-confirmation/1.0.7/bootstrap-confirmation.min.js') }}
{{ Html::script('https://cloud.tinymce.com/stable/tinymce.min.js') }}
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>

@yield('after-scripts')

<script>
	$(".fancybox").fancybox();
	$('body').confirmation({
		placement       : 'left',
		selector: '[data-toggle="confirmation"]',

	});
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$(document).ready(function() {
		setInterval(function(){
			$('.alert').hide('slow/400/fast');
		},6000);
	});
	$(function () {
		$('.select2').select2();
	});
	var url = window.location;

	$('ul.sidebar-menu a').filter(function() {
		return this.href == url;
	}).parent().addClass('active');

	$('ul.treeview-menu a').filter(function() {
		return this.href == url;
	}).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');


	// Gallery image hover
	$( ".img-wrapper" ).hover(
		function() {
			$(this).find(".img-overlay").animate({opacity: 1}, 600);
		}, function() {
			$(this).find(".img-overlay").animate({opacity: 0}, 600);
		}
		);

// Lightbox
var $overlay = $('<div id="overlay"></div>');
var $image = $("<img>");
var $prevButton = $('<div id="prevButton"><i class="fa fa-chevron-left"></i></div>');
var $nextButton = $('<div id="nextButton"><i class="fa fa-chevron-right"></i></div>');
var $exitButton = $('<div id="exitButton"><i class="fa fa-times"></i></div>');

// Add overlay
$overlay.append($image).prepend($prevButton).append($nextButton).append($exitButton);
$("#gallery").append($overlay);

// Hide overlay on default
$overlay.hide();

// When an image is clicked
$(".img-overlay").click(function(event) {
  // Prevents default behavior
  event.preventDefault();
  // Adds href attribute to variable
  var imageLocation = $(this).prev().attr("href");
  // Add the image src to $image
  $image.attr("src", imageLocation);
  // Fade in the overlay
  $overlay.fadeIn("slow");
});

// When the overlay is clicked
$overlay.click(function() {
  // Fade out the overlay
  $(this).fadeOut("slow");
});

// When next button is clicked
$nextButton.click(function(event) {
  // Hide the current image
  $("#overlay img").hide();
  // Overlay image location
  var $currentImgSrc = $("#overlay img").attr("src");
  // Image with matching location of the overlay image
  var $currentImg = $('#image-gallery img[src="' + $currentImgSrc + '"]');
  // Finds the next image
  var $nextImg = $($currentImg.closest(".image").next().find("img"));
  // All of the images in the gallery
  var $images = $("#image-gallery img");
  // If there is a next image
  if ($nextImg.length > 0) {
    // Fade in the next image
    $("#overlay img").attr("src", $nextImg.attr("src")).fadeIn(800);
} else {
    // Otherwise fade in the first image
    $("#overlay img").attr("src", $($images[0]).attr("src")).fadeIn(800);
}
  // Prevents overlay from being hidden
  event.stopPropagation();
});

// When previous button is clicked
$prevButton.click(function(event) {
  // Hide the current image
  $("#overlay img").hide();
  // Overlay image location
  var $currentImgSrc = $("#overlay img").attr("src");
  // Image with matching location of the overlay image
  var $currentImg = $('#image-gallery img[src="' + $currentImgSrc + '"]');
  // Finds the next image
  var $nextImg = $($currentImg.closest(".image").prev().find("img"));
  // Fade in the next image
  $("#overlay img").attr("src", $nextImg.attr("src")).fadeIn(800);
  // Prevents overlay from being hidden
  event.stopPropagation();
});

// When the exit button is clicked
$exitButton.click(function() {
  // Fade out the overlay
  $("#overlay").fadeOut("slow");
});


</script>
@yield('end-scripts')
</body>
</html>
