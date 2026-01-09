@extends('layouts.app')
@section('content')
@include('layouts.sidebar')
<section>
    <!-- PAGE CONTENT -->
    <div class="containerLMS">
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbsBack">
                <a href="{{ url($path.'/dashboard') }}"> Dashboard</a> |
                <a href="{{ url($path.'/dashboard/curricula') }}"> Curricula</a> |
                <span style="font-weight: bold;color:#9acd57">{{$package->name ?? 'Curriculum'}}</span> |
                <a href="#l4"><i class="fas fa-long-arrow-alt-down fa-fw"></i> Jump to individual files table</a>
            </div>
        </div>
        <!-- Package HEADER-->
        <div class="row mx-1">
            <!-- Welcome -->
            <div class="col-8">
                <hr>
                @if($package)
                <p class="sketchnote-square">Welcome to the UN|HUSHED {{$package->name}} Curriculum!</b></p>
                @else
                <div class="alert alert-warning">Curriculum not found.</div>
                @endif
            </div>
        </div>
        <div class="row mx-1">
            <div class="col-8">
                <p>We are so glad you’re here. You can use the table of contents to jump to the content you need. If this is your first time using the curriculum we highly encourage you read everything in order.</p>
                <hr>
            </div>
        </div>
        <div class="row mx-1">
            <!-- Table of Contents -->
            <div class="col-auto" style="text-align: justify">
                <p class="sketchnote-square">Table of Contents</p>
                <p>• <a href="#l1">How to use the digital interface</a>
                <br>• <a href="#l2">Facilitator Guide</a>
                <!-- <br>• <a href="#l3">Curriculum by Unit</a> -->
                <br>• <a href="#l4">Curriculum by Individual File</a></p>
                </div>
            <div class="col-auto center float-end">
                @if($package)
                <img class="med" src="{{ url('uploads/products/'.($package->primary_image_path ?? $package->image)) }}">
                @endif
            </div>
        </div>
        <!-- How to use the digital interface -->
        <div class="row mx-1 anchor" id="l1">
            <div class="col-8" style="text-align: justify">
                <hr>
                <p class="sketchnote-square">How to use the digital interface</p>
                <p>Because we update our curriculum so regularly, keeping our files - and your files! - in order is more complex than it is for most curricula.</p>
                <p>The Facilitator Guide comes first. This file offers a cohesive, comprehensive conversation about teaching the UN|HUSHED {{$package->name}} Curriculum.
                    It is, in some ways, like an introduction to the book, only much, much more valuable! It includes guidance, for example, on how to talk with parents,
                    how to answer anonymous questions, and how to set up your sex ed classrooms.</p>
                <p id="l2">After the Facilitator Guide, you have two choices when it comes to accessing the curricula:</p>
                    <ol>
                        <li><a href="#l3"><b>Curriculum by Unit:</b></a>
                            By updating your files once a year, downloading each cohesive unit. These large, unit-specific files are in the first set of links below. We gather everything new into these files in August and send out an email when it happens. Please note, you will still need to use the second method to download PowerPoints.
                        <li><a href="#l4"><b>Curriculum by Individual File:</b></a>
                            By downloading each file independently and then updating them every time we have a new one available. These are in the second set of links below. We update these files continuously through the year as we have new ideas, need to update broken links, etc. There are details on downloading and using these more frequently updated links below.
                        </ol>
                <p>We find that some facilitators prefer to just download the new collected file every year while others prefer to have each file individually stored on their computer and update only those files that need updating. We’re sure you’ll pick the right file management system for you!
            </div>
        </div>
        <!-- Begin with the Facilitator Guide -->
        <div class="row mx-1 anchor" id="l2">
            <div class="col-8" style="text-align: justify">
                <hr>
                <p class="sketchnote-square">Begin with the Facilitator Guide</p>
                <p>The Facilitator Guide is a critical element of every curriculum. It is a handbook for how to use the curriculum, in keeping with the ways the authors intended it to be used.</p>
            </div>
            <div class="col-12">&nbsp;</div>
            <div class="col-4">
                <table class="table table-hover border table-borderless" id="fgTable">
                    <thead>
                        <tr>
                            <th style="text-align:center; width: 100%;" class="align-middle bg-primary text-white">FACILITATOR GUIDE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($package)
                        @foreach($units as $u)
                        @foreach($u->sessions() as $s)
                        @foreach($s->documents()->where('name', '<b>Facilitator Guide</b>') as $d)
                        <!-- document -->
                            <td style="text-align:right; width: 20%;"   class="align-middle">
                                <a href="{{ curriculum_doc_url($d->filenfo) }}" target="_blank" rel="noopener noreferrer"><i class="fa-solid fa-file-arrow-down fa-fw"></i></a></td>
                            <td style="text-align:left; width: 40%;"    class="align-middle">
                                <a href="{{ curriculum_doc_url($d->filenfo) }}" target="_blank" rel="noopener noreferrer">{!!$d->name!!}</a>
                            </td>
                            <td style="text-align:center; width: 40%;"  class="align-left">{{date('m/d/Y', strtotime($d->updated_at))}}</td>
                        @endforeach
                        @endforeach
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <!--  Curriculum by Unit -->
        <!-- <div class="row mx-1 anchor" id="l3">
            <div class="col-8" style="text-align: justify">
                <hr>
                <p class="sketchnote-square">Curriculum by Unit</p>
                <p>These packages include the entire collection of pdfs by unit. They are updated annually versus the more frequent updates we do in the individual files further below. Keep in mind that the Curriculum by Individual File will still be useful for you, as you will find links and digital resources there that cannot be included in a downloadable file.</p>
                </div>
            <div class="col-12">&nbsp;</div>
            <div class="col-4">
                <table class="table table-hover border table-borderless" id="fullTable">
                    <thead>
                        <tr>
                            <th style="text-align:center; width: 60%;" class="align-middle bg-primary text-white">SESSIONS BY UNIT</th>
                            <th style="text-align:center; width: 40%;" class="align-middle bg-primary text-white">UPDATED</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="text-align:right; width: 20%;" class="align-middle"><a href="#" target="_blank"><i class="fas fa-download"></i></a></td>
                            <td style="text-align:left; width: 40%;" class="align-middle">Being packaged now... ETA for 2023 updates is December.</td>
                            <td style="text-align:center; width: 40%;" class="align-middle"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> -->

        <!-- Curriculum by File -->
        <div class="row mx-1 anchor" id="l4">
            <div class="col-8" style="text-align: justify">
                <hr>
                <p class="sketchnote-square">Curriculum by Individual File</p>
                <p>This file structure allows facilitators to view/print only the pages they need/want for specific sessions, rather than the larger files above.
                    The content is exactly the same at the begining of the year. The files below, however, are updated throughout the year as we make benefitial changes.
                    You can track those changes in the updated column. We also send out emails.
                    Remember, if you have not taught this curriculum, start with the big-picture, <a href="#l2">facilitator guides</a>. Now, on to the structure below.</p>
                <p><b><u>Table structure:</u></b>
                    <br><b>UNIT</b> this column (far left) shows the unit name and number each document/link belong to.
                    <br><b>SESSION</b> this column shows the Session number each document/link belong to.
                    <br><b><i class="fa-solid fa-eye"></i>/<i class="fas fa-download"></i> DOCUMENT NAME</b> shows the name of the document as a clickable link to view and download files or visit links.
                    <br><b>MINUTES</b> shows how many minutes a session may take to teach.
                    <br><b>KEYWORDS</b> You can search these keywords to quickly find materials. 
                        <span style="color:#9acd57; font-weight:bold">PC:</span> Press "Control + F".</span>
                        <span style="color:#9acd57; font-weight:bold">Mac:</span> Press "Command + F".</span>
                    <br><b>UPDATED</b> shows the date the file was last updated. Recently updated indicates the file is 90 days or newer
                </p>
                <p><b><u>Document structure:</u></b>
                    <br><b>Unit:</b> is a grouping of session.
                    <br><b>Intro:</b> This is the first file in each unit. This file describes the sessions, including pedagogical insights.
                    <br><b>Session:</b> These files include each session’s purpose, objectives, key messages, agenda, materials, planning notes, and a step-by-step procedure for each activity.
                    <br><b>Additional Resources:</b> Each session includes additional resources. These can be handouts, Powerpoints, Google slides, links, etc.
                        <br><i class="fa-solid fa-arrow-turn-down-right"></i> <b>FR:</b> These are designed to be used by the facilitator, providing either useful information, cards or other elements to be copied, cut apart, or otherwise manipulated and then given to participants.
                        <br><i class="fa-solid fa-arrow-turn-down-right"></i> <b>H:</b> are designed to be copied and given to participants
                        <br><i class="fa-solid fa-arrow-turn-down-right"></i> <b><i class="fas fa-link fa-fw"></i></b>: a link to an online resource
                        <br><i class="fa-solid fa-arrow-turn-down-right"></i> <b>PP:</b> a Powerpoint
                        <br><i class="fa-solid fa-arrow-turn-down-right"></i> <b>GS:</b> a Google slide version of the above Powerpoint.
                </p>
            </div>
            <div class="col-12">&nbsp;</div>
            <div class="col-10">
                <table class="table table-hover border table-borderless table-striped" id="hsTable">
                    <thead>
                        <tr>
                            <th style="text-align:center; width: 2%;"   class="align-middle bg-primary text-white"></th>
                            <th style="text-align:center; width: 13%;"  class="align-middle bg-primary text-white">UNIT</th>
                            <th style="text-align:center; width: 6%;"   class="align-middle bg-primary text-white">SESSION</th>
                            <th style="text-align:center; width: 33%;"  class="align-middle bg-primary text-white"><i class="fa-solid fa-eye"></i>/<i class="fas fa-download"></i> DOCUMENT NAME</th>
                            <th style="text-align:center; width: 8%;"   class="align-middle bg-primary text-white">MINUTES</th>
                            <th style="text-align:center; width: 28%;"  class="align-middle bg-primary text-white">KEYWORDS</th>
                            <th style="text-align:center; width: 10%;"  class="align-middle bg-primary text-white">UPDATED</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($package)
                        @foreach($units as $u)
                        @foreach($u->sessions() as $s)
                        @foreach($s->documents() as $d)
                        <tr>
                            <td style="text-align:center; width: 2%;"></td>
                            <!-- unit -->
                            <td style="text-align:left; width: 13%;" class="align-middle">{{str_pad($u->number, 2, 0, STR_PAD_LEFT)}}  {!!$u->name!!}</td>
                            <!-- session -->
                            <td style="text-align:center; width: 5%;" class="align-middle">
                                @if($s->number == 0)
                                    -
                                @else
                                    {{str_pad($s->number, 2, 0, STR_PAD_LEFT)}}
                                @endif
                            </td>
                            <!-- document -->
                            <td style="text-align:left; width: 33%;" class="align-middle">
                                @empty($d->filenfo)
                                    <a href="{{ url($d->url) }}" target="_blank"><i class="fas fa-link fa-lg fa-fw"></i> {!!$d->name!!}</a>
                                @else
                                    <a href="{{ curriculum_doc_url($d->filenfo) }}" target="_blank" rel="noopener noreferrer"><i class="fa-solid fa-file-arrow-down fa-fw"></i> {!!$d->name!!}</a>
                                @endempty
                            </td>
                            <td style="text-align:center; width: 8%;" class="align-middle">{{$d->time}}</td>
                            <td style="text-align:left; width: 26%;" class="align-middle">
                                @if($d->updated_at > now()->subDays(90)->endOfDay())
                                    <b>RECENTLY UPDATED,</b>
                                @endif
                                {{$d->keywords}}
                            </td>
                            <td style="text-align:center; width: 10%;" class="align-left">{{date('m/d/Y', strtotime($d->updated_at))}}</td>
                        </tr>
                        @endforeach
                        @endforeach
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="col-12 mb-5">&nbsp;</div>
        </div>
    </div>

    <style type="text/css" media="all">
        .anchor {
            scroll-margin-top: 125px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            background-color: #265a8e;
            border-radius: 10px;
            overflow: hidden;
        }
        thead, tbody, tr, td, th {
            display: block;
        }
        tr:after {
            content: ' ';
            display: block;
            visibility: hidden;
            clear: both;
        }
        thead th {
            height: auto;
            line-height: 30px;
            border: 0 !important;
        }
        tbody {
            max-height: 600px;
            overflow-y: auto;
            background-color: #fff;
        }
        thead {
            width: 97%;
            width: calc(100% - 17px);
            color: white;
            font-size: 10px;
            padding: 1px;
        }
        tbody td, thead th {
            float: left;
        }
        tbody td:last-child, thead th:last-child {
            border-right: none;
        }
    </style>
</section>
@endsection
