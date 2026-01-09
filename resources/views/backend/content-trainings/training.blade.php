@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <div class="row mx-auto">
            <div class="col-10 mx-5 mt-2">
                <div class="nav-box">
                    <h3>{{ $training->name }}</h3>
                </div>
            </div>
        </div>
    <div class="row mx-auto">
            <div class="col-10 mx-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-3 text-center align-self-center">
                                <a href="https://drive.google.com/file/d/13UcAwaR3Z0xotPCkW6PdT5M7YCkpuR5r/view?usp=sharing" target="_blank"><img src="/img/trainings/intros/thumb-hcwp.png" alt="Play Video"></a>
                            </div>
                            <div class="col-9 text-justify">
                                <b><span style="color:#9acd57;font-weight:bold;font-size:16px">Session:</span> {{ $training->name }}</b>
                                <p class="text-justify"><span style="font-weight:light;font-size:12px">recorded on date.</span>
                                <br>{{ $training->description }}</div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12 text-center">
                                <b><span style="color:#9acd57">Session Resources</span></b>
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1"></div>
                            <div class="col-11">
                                <a href="{{ asset('pdfs/t-hcwp/UNHUSHED_Intro-To-Sexuality-Education-A-Handbook-for-Child-Welfare-Providers.pdf') }}"
                                download="UNHUSHED_Intro-To-Sexuality-Education-A-Handbook-for-Child-Welfare-Providers.pdf" class="px-1">
                                <i class="fal fa-book fa-fw fa-lg align-middle"></i></a>
                                Link
                            </div>
                            <div class="col-12">&nbsp;</div>
                            <div class="col-1"></div>
                            <div class="col-11">
                                <a href="{{ asset('pdfs/t-hcwp/UNHUSHED_H-MedicalVisitChecklist_v02.pdf') }}"
                                download="UNHUSHED_H-MedicalVisitChecklist_v02.pdf" class="px-1">
                                <i class="fas fa-file-alt fa-fw fa-lg align-middle"></i></i></a>
                                Download
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                        @include('backend.content-trainings.pdf-instruct')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal video 1 -->
    <div id="video1" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                    <!-- <iframe src="#" width="640" height="360" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe> -->
                    Video Coming Soon.
                </div>
            </div>
        </div>
    </div>

    <script>
        $("#video1").on('hidden.bs.modal', function (e) {
            $("#video1 iframe").attr("src", $("#video1 iframe").attr("src"));
        });
    </script>
</section>
@endsection






