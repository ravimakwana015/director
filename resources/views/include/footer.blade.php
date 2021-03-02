<footer>
    <div class="footer-wrap">
        <ul>
            <li><a href="{{ route('contact.us') }}">Contact & Help</a></li>
            @foreach(pages() as $page)
                @if($page['title'] == 'About' || $page['title'] == 'About-us' || $page['title'] == 'About us' || $page['title'] == 'about-us' || $page['title'] == 'about' || $page['title'] == 'about us')
                @else
                    <li><a href="{{ route('page',$page->slug) }}" title="{{ $page->title }}">{{ $page->title }}</a></li>
                @endif
            @endforeach
        </ul>
    </div>
    <span id="siteseal">
        <script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=B2GtQihiteK90nZ5vkukQFlq3T0MF4eeWOX7MbSZgDp8qrffXDpGGOVlt3cY"></script>
    </span>
</footer>
