@extends('layouts.app')
@section('content')
<section style="background:#f0f3fa;">
  <!-- breadcrumbs -->
  <div class="crumbBar">
    <div class="breadcrumbs">
      <a href="/">Home </a> |
      <a href="{{ url($path.'/support') }}">Client Support </a> |
      <span style="font-weight: bold;color:#9acd57">Curriculum Review</span>
    </div>
  </div>

  <!-- REVIEW CARD-->
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="mx-auto my-3" style="max-width: 500px;">
          <div class="loginCard p-3">
            <div class="card-body">
              <img class="img-s" src="{{ asset('img/octos/ollie-computer.png') }}" alt="Ollie the octopus holding up a laptop">
              <p class="diazo" style="font-size:35px;">Curriculum Review Request</p>
              <span style="text-align:center;">
                Answer the following questions with the Review Team Leader's information (not your own if that's not you.)
              </span>
              <div class="_form_12"></div>
              <script src="https://unhushed.activehosted.com/f/embed.php?id=12" charset="utf-8"></script>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</section>
@endsection


