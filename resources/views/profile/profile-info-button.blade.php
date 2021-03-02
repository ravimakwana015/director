@if(Auth::user() && Auth::user()->id!=$user->id)

    <div class="profile-like-chat">
        <div class="Like">
            @include('profile.user-gallery-insta')
        </div>
        <div class="brand-img-like">
            <div class="img-wrap-like">
                {{-- <button type="button" class="btn @if($user->user_type=='1') bg-actor @endif @if($user->user_type=='2') bg-model @endif @if($user->user_type=='3')
                    bg-musician @endif" data-toggle="modal" data-target="#exampleModalCenter"> Inquire
                </button> --}}
                <a href="{{ route('chat.show', $user->id) }}" class="btn @if($user->user_type=='1') bg-actor @endif @if($user->user_type=='2') bg-model @endif
                @if($user->user_type=='3') bg-musician @endif">Chat With User </a>
                <a href="{{ route('my-network',[str_replace(' ', '-', strtolower($user->username))]) }}" title="View Feed" class="btn @if($user->user_type=='1') bg-actor @endif
                @if($user->user_type=='2') bg-model @endif @if($user->user_type=='3') bg-musician @endif"><i class="fa fa-rss" aria-hidden="true"></i> View Feed</a>
                {{-- Add and remove friend --}}
                @include('profile.network')
            </div>
        </div>
    </div>

@else

    <div class="profile-like-chat">
        <div class="Like">
            @include('profile.user-gallery-insta')
        </div>
        @if(isset(Auth::user()->id) && Auth::user()->id==$user->id)
        @else
            <div class="brand-img-like">
                <div class="img-wrap-like">
                    {{-- <button type="button" class="btn @if($user->user_type=='1')bg-actor @endif @if($user->user_type=='2') bg-model @endif @if($user->user_type=='3')
                        bg-musician @endif" id="messageContact"> Inquire
                    </button> --}}
                    <a href="{{ route('chat.show', $user->id) }}" class="btn @if($user->user_type=='1')bg-actor @endif @if($user->user_type=='2') bg-model @endif
                    @if ($user->user_type=='3') bg-musician @endif">Chat With User </a>
                    <a href="{{ route('my-network',[str_replace(' ', '-', strtolower($user->username))]) }}" title="View Feed" class="btn @if($user->user_type=='1') bg-actor @endif
                    @if($user->user_type=='2') bg-model @endif @if($user->user_type=='3') bg-musician @endif"><i class="fa fa-rss" aria-hidden="true"></i> View Feed</a>
                </div>
            </div>
        @endif
    </div>

@endif
