<div class="gallery-icon"><i class="far fa-images"></i>
    <span class="gallery-count">{{ count($user->gallery) }}</span>
</div>
<div class="video-icon"><i class="fas fa-video"> </i>
    <span class="video-count">{{ count($user->videoGallery) }}</span>
</div>
@if(isset($user->instagram_link))
    <div class="instagram-icon">
        <a target="_blank" href="{{ addScheme($user->instagram_link) }}">
            <img src="{{ asset('public/front/images/instagram.svg') }}" height="20" width="20">
        </a>
    </div>
@else
    <div class="instagram-icon">
        <a title="No Link Available" href="javascript:;">
            <img src="{{ asset('public/front/images/instagram.svg') }}" height="20" width="20">
        </a>
    </div>
@endif

@if(isset($user->city))
    <div class="city-icon"><i class="fas fa-thumbtack"></i> {{ $user->city }}</div>
@endif
