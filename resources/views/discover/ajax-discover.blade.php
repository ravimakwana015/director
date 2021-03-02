<div class="career-listing">
    <div class="isotope items">
        @foreach($discovers as $discover)

            @if($discover->competitions=='Writing competitions')
                <div class="item writing">
                    @include('discover.discover_part')
                </div>
            @elseif($discover->competitions=='Filming competitions')

                <div class="item filming">
                    @include('discover.discover_part')
                </div>

            @elseif($discover->competitions=='Singing competitions')

                <div class="item singing">
                    @include('discover.discover_part')
                </div>

            @elseif($discover->competitions=='Best photo competitions')

                <div class="item bestphoto">
                    @include('discover.discover_part')
                </div>

            @endif
        @endforeach
    </div>
    <div class="forum-pagination">
        {!! $discovers->appends(request()->except('page'))->links() !!}
    </div>
</div>
