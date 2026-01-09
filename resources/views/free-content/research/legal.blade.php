@extends('layouts.app')

@section('content')
    <section>
        <!-- breadcrumbs -->
        <div class="crumbBar">
            <div class="breadcrumbs">
                <a href="{{ url($path.'/free-content') }}">Free Content </a> |
                <a href="{{ url($path.'/research') }}">Research</a> |
                Legal |
                <a href="{{ url($path.'/pedagogy') }}">Pedagogy</a> |
                <a href="{{ url($path.'/statistics') }}">Statistics</a>
            </div>
        </div>

        <!-- DICTIONARY HEADER-->
        <div class="container">
            <div class="d-flex justify-content-center">
                <div class="row">
                    <div class="col-auto mx-auto px-2">
                        <p class="diazo text-center">US Laws that affect Sexuality Education by State</p>
                        This page is only available to those with the direct link attending our Sex Ed & the Law series. It is a working document at the moment. We intend to release this for free once it is complete.</p>
                        </p>
                    </div>
                </div>
            </div>

        <!-- PEDAGOGY CONTENT-->
        <div class="row justify-content-center mx-auto" style="max-width:100%">
            <!-- search bar -->
            <div class="ml-auto pr-4">
                <form action="{{ url($path.'legal') }}" method="GET" role="search">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="term" placeholder="Search keywords" id="term">
                        <div class="input-group-append">
                            <button class="btn btn-secondary" type="submit" title="Search keywords">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                            <a href="{{ url('legal') }}" button class="btn btn-outline-secondary" type="button" title="Refresh page">
                                <i class="fas fa-sync-alt"></i></a>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- entries -->
            <div class="d-flex justify-content-center">
                <div class="row">
                    <div class="col-auto mx-auto mb-5">
                        <div class="card">
                            <table class="table table-striped table-bordered" style="width: 100%" id="legalTable">
                                <thead>
                                    <tr>
                                        <th id="state" colspan="1" style="text-align:center">State</th>
                                        <th id="optin" colspan="2" style="text-align:center" bgcolor="#809cdd">Sex Education Opt In/Out Policy</th>
                                        <th id="distribute" colspan="2" style="text-align:center" bgcolor="#7cbcfb">Distribution of Obscene Materials to Minors Exemption for Education</th>
                                        <th id="medical" colspan="2" style="text-align:center" bgcolor="#d6faa6">Medical Accuracy From NCSL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Alabama</th>
                                        <td bgcolor="#7692d2">Ala Code § 16-40A-2</td>
                                        <td bgcolor="#7692d2">Out</td>
                                        <td bgcolor="#70b0ef">Ala. Code § 13A-12-200.5</td>
                                        <td bgcolor="#70b0ef">(3) The following shall be affirmative defenses to a charge of violating this section as it may relate to a particular minor:
                                            ...
                                            d. The act charged was done for a bona fide medical, scientific, educational, legislative, judicial or law enforcement purpose.</td>
                                        <td bgcolor="#ccf29a"></td>
                                        <td bgcolor="#ccf29a"></td>
                                    </tr>
                                    <tr>
                                        <th>Alaska</th>
                                        <td bgcolor="#809cdd"></td>
                                        <td bgcolor="#809cdd">Out</td>
                                        <td bgcolor="#7cbcfb">Alaska Stat. Ann. § 11.61.128  </td>
                                        <td bgcolor="#7cbcfb">(c) In this section, “harmful to minors” means
                                            (1) the average individual, applying contemporary community standards, would find that the material, taken as a whole, appeals to the prurient interest in sex for persons under 16 years of age;
                                            (2) a reasonable person would find that the material, taken as a whole, lacks serious literary, artistic, educational, political, or scientific value for persons under 16 years of age; and</td>
                                        <td bgcolor="#d6faa6"></td>
                                        <td bgcolor="#d6faa6"></td>
                                    </tr>
                                    <tr>
                                        <th>Arizona</th>
                                        <td bgcolor="#7692d2">Ariz Rev Stat §15-716</td>
                                        <td bgcolor="#7692d2">Out (HIV)/In (Sex Ed)</td>
                                        <td bgcolor="#70b0ef">A.R.S. § 13-3506</td>
                                        <td bgcolor="#70b0ef">Possession of obscene material and pornography for viewing or reading within one's home is protected by First Amendment, as would be a showing and distribution of photographic materials for bona fide scientific or educational use. NGC Theatre Corp. v. Mummert (1971) 107</td>
                                        <td bgcolor="#ccf29a">Ariz. Rev. Stat. § 15-716</td>
                                        <td bgcolor="#ccf29a">Each school district may provide instruction on HIV/AIDS. At minimum the instruction shall be medically accurate, age-appropriate, promote abstinence, discourage drug abuse and dispel myths regarding the transmission of HIV.</td>
                                    </tr>
                                    <tr>
                                        <th>Arkansas</th>
                                        <td bgcolor="#809cdd"></td>
                                        <td bgcolor="#809cdd">Out</td>
                                        <td bgcolor="#7cbcfb">Alaska Stat. Ann. § 11.61.128  </td>
                                        <td bgcolor="#7cbcfb">(c) In this section, “harmful to minors” means
                                            (1) the average individual, applying contemporary community standards, would find that the material, taken as a whole, appeals to the prurient interest in sex for persons under 16 years of age;
                                            (2) a reasonable person would find that the material, taken as a whole, lacks serious literary, artistic, educational, political, or scientific value for persons under 16 years of age; and</td>
                                        <td bgcolor="#d6faa6"></td>
                                        <td bgcolor="#d6faa6"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection
