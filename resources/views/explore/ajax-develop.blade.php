<div class="career-listing">
    <div class="isotope items">
        @foreach($explore as $explores)
            @if($explores->job_type=='Actor')
                <div class="item actors">
                    @include('explore.explore_part')
                </div>
            @elseif($explores->job_type=='Musicians')

                <div class="item musicians">
                    @include('explore.explore_part')
                </div>

            @elseif($explores->job_type=='Models')

                <div class="item models">
                    @include('explore.explore_part')
                </div>

            @endif
        @endforeach
    </div>
    <div class="forum-pagination">
        {!! $explore->appends(request()->except('page'))->links() !!}
    </div>
</div>
