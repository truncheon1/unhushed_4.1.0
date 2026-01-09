<div class="modal fade" id="modal-emp-notice-internal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Testing</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <td style="text-align:left">
            @if(isset($details))
              @foreach($details as $detail)
                {{$detail['name']}}
                <br/>{{$detail['address1']}} 
                @if(isset($detail['address2']))
                <br/>{{$detail['address2']}}
                @endif
                <br/>{{$detail['city']}}, {{$detail['state']}} {{$detail['zip']}}
                </td>
              @endforeach
            @endif
        </div>
    </div>
</div>
