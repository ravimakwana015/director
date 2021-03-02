<?php echo '<?xml version="1.0" encoding="UTF-8"?>'?>
<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc>{{ URL::to('/') }}</loc>
	</url>
	<url>
		<loc>{{ route('contact.us') }}</loc>
	</url>
	<url>
		<loc>{{ route('instagram') }}</loc>
	</url>
	<url>
		<loc>{{ route('career') }}</loc>
	</url>
	<url>
		<loc>{{ route('forum') }}</loc>
	</url>
	<url>
		<loc>{{ route('forumlist') }}</loc>
	</url>
	<url>
		<loc>{{ route('profile') }}</loc>
	</url>
	<url>
		<loc>{{ route('users') }}</loc>
	</url>
	<url>
		<loc>{{ route('dashboard') }}</loc>
	</url>
	@foreach ($slug as $slugname)
	<url>
		<loc>{{ route('page',$slugname->slug) }}</loc>
	</url>
	@endforeach
</urlset>
