<div class="career-listing">
    <div class="isotope items">
        @foreach($careers as $career)
            @if($career->job_type=='Actor')
                <div class="item actors">
                    @include('career.career_part')
                </div>
            @elseif($career->job_type=='Musicians')
                <div class="item musicians">
                    @include('career.career_part')
                </div>
            @elseif($career->job_type=='Models')
                <div class="item models">
                    @include('career.career_part')
                </div>
            @endif
        @endforeach
    </div>
    <div class="forum-pagination">
        {!! $careers->appends(request()->except('page'))->links() !!}
    </div>
</div>
