<ul class="timeline timeline-inverse">
    @foreach($forum as $key => $comment)
        <li>
            <i class="fa fa-comments bg-yellow"></i>
            <div class="timeline-item">
                <div class="timeline-body">
                    Topic - {!! $comment->topic !!} - {!! $comment->created_at !!}
                </div>
                @foreach($comment->comments as $key => $viewcomment)
                    @if(isset($viewcomment->usercomment))
                        <div class="timeline-footer">
                            {!! $viewcomment->usercomment->first_name !!} {!! $viewcomment->usercomment->last_name !!} -
                            <a class="btn btn-warning btn-flat btn-xs">{!! $viewcomment->comment !!} </a> - {!! $viewcomment->created_at !!}
                        </div>
                    @endif
                @endforeach
            </div>
        </li>
    @endforeach
</ul>
