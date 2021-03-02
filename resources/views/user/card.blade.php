<table class="table">
    <tbody>
    <thead>
    <tr>
        <th>Brand</th>
        <th>Number</th>
        <th>Expires</th>
        <th>Status</th>
        <th style="text-align: center;">Action</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($cards) && count($cards))
        @foreach($cards as $key=>$card)
            <tr>
                <td>{!! $card->card->brand !!}</td>
                <td>....{!!  $card->card->last4 !!}</td>
                <td>{{ $card->card->exp_month }} / {{ $card->card->exp_year }}</td>
                <td>
                    @if(isset($defaultPaymentMethod) && $defaultPaymentMethod!='' && $defaultPaymentMethod==$card->id)
                        <span>Default</span>
                    @endif
                </td>
                <td style="text-align: center;color: #2b2f2e;">
                    <a href="javascript:;" onclick="deleteCardModal('{{ $card->id }}','{{ route('delete-card') }}')" style="color: #2b2f2e;"><i class="fas fa-trash-alt"></i></a>&nbsp;&nbsp;<a
                        href="javascript:;" onclick="getCardModal('{{ $card->id }}','{{ route('get-card') }}')" style="color: #2b2f2e;"><i class="fas fa-pencil-alt"></i></a>
                    @if(isset($defaultPaymentMethod) && $defaultPaymentMethod!='' && $defaultPaymentMethod!=$card->id)
                        <div class="set-default-wrap" >
                            <span onclick="showDefault('{{ $key }}')">···</span>
                            <div class="set-default" id="default_{{ $key }}">
                                <a href="javascript:;" onclick="setDefaultCard('{{ $card->id }}','{{ route('set-default-card') }}','{{ route('dashboard') }}')">Set as default</a>
                            </div>
                        </div>
                    @endif
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>

