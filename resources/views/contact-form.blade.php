<div class="contact-form-wrap">
    <h2>Send Us A Message</h2>
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
    <form method="post" action="{{ route('send.message') }}">
        @csrf
        <div class="form-item">
            <input type="text" name="name" placeholder="Enter Your Name" value="{{ old('name') }}">
            <span class="icon"><i class="far fa-user"></i></span>
            @if ($errors->has('name'))
                <span class="text-danger">{{ $errors->first('name') }}</span>
            @endif
        </div>
        <div class="form-item">
            <input type="email" name="email" placeholder="Enter Your Email" value="{{ old('email') }}">
            <span class="icon"><i class="far fa-envelope"></i></span>
            @if ($errors->has('email'))
                <span class="text-danger">{{ $errors->first('email') }}</span>
            @endif
        </div>
        <div class="form-item">
            <input type="text" name="contact_number" placeholder="Enter Your Contact Number"
                   value="{{ old('contact_number') }}">
            <span class="icon"><i class="fas fa-phone"></i></span>
            @if ($errors->has('contact_number'))
                <span class="text-danger">{{ $errors->first('contact_number') }}</span>
            @endif
        </div>
        <div class="form-item">
            <textarea placeholder="Enter Message here..." name="message"></textarea>
            @if ($errors->has('message'))
                <span class="text-danger">{{ $errors->first('message') }}</span>
            @endif
        </div>
        <div class="form-action">
            <button class="btn">Send Message</button>
        </div>
    </form>
</div>
