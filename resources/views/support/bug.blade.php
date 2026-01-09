@extends('layouts.app')
@section('content')
<section style="background:#f0f3fa;">
  <!-- breadcrumbs -->
  <div class="crumbBar">
      <div class="breadcrumbs">
          <a href="/">Home </a> |
          <a href="{{ url($path.'/support') }}">Client Support </a> |
          <span style="font-weight: bold;color:#9acd57">Report a Bug</span>
      </div>
  </div>

  <!-- BUG CARD-->
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="mx-auto my-3" style="max-width: 500px;">
          <div class="loginCard p-3">
            <div class="card-body">
                <img class="img-s" src="{{ asset('img/octos/ollie-bug.png') }}" alt="Ollie the octopus holding a lady bug">
                <p class="diazo">Report a Bug</p>
                <div class="_form_13"></div>
                <script src="https://unhushed.activehosted.com/f/embed.php?id=13" charset="utf-8"></script>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>
@endsection


