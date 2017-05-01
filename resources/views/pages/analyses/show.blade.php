@extends('layout.HUdefault')
@section('title', 'Analyses - Show')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7">
                <h1>{{ Lang::get('analyses.title') }}</h1>
                <dl>

                    <dt>Name</dt>
                    <dd class="well">{{ $analysis->name }}</dd>

                    <dt>Cached for</dt>
                    <dd class="well">{{ $analysis->cache_duration }} {{ $analysis->type_time }}</dd>

                    <dt>Query</dt>
                    <dd class="well">{{ $analysis->query }}</dd>

                    <dt>Data</dt>
                    <dd>
                        <?php if (isset($analysis_result['error'])): ?>
                        An error occured during the query:
                        <pre><code>{{ $analysis_result['error'] }}</code></pre>
                        <?php else: ?>
                        <pre><code>{{ json_encode($analysis_result['data'],  JSON_PRETTY_PRINT) }}</code></pre>
                        <?php endif ?>
                    </dd>
                </dl>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-7">
                <form action="{{ route('analyses-expire') }}" method="post" accept-charset="UTF-8">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $analysis->id }}">
                    <button class="btn btn-default" type="submit">Expire data</button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <a href="{{ route('bugreport') }}"><img src="{{ secure_asset('assets/img/bug_add.png') }}" width="16px"
                                                        height="16px"/> Heb je tips of vragen? Geef ze aan ons door!</a>
            </div>
        </div>
    </div>
@stop