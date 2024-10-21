@php use App\Models\Category;use App\Models\Tool; @endphp
@props(['articles'=>[],
     'search_string'=>'',
     'available'=>null,
     'category'=>null,
     'tool'=>null,
 ])
@extends('layouts.public')
@section('title', 'Catalogo')

@php
    $args = [
        'search_string' => $search_string ?? null,
        'available' => $available ?? null,
        'category' => $category ?? null,
        'tool' => $tool ?? null,
        ];

    $categories=Category::all()->map(fn($it)=>$it->name);
    $tools=Tool::all()->map(fn($it)=>$it->name);
@endphp

@section('content')
    <div class="row padding" style="width: auto">

        {{--Filtering--}}
        <div class="sidebar">
            <div class="filter--item">
                <h3> Disponibilità </h3>
                <ol>
                    <li><a class="clickable @if($available==null)filter--selected @endif filter--default"
                           href="{{request()->fullUrlWithQuery(['available'=>null])}}"> Qualsiasi </a></li>
                    @foreach( ['Disponibile subito'=>true, 'Su ordinazione'=>false] as $availabilityKey=>$availabilityValue)
                        <li>
                            <a class="clickable @if($available!=null && $available==$availabilityValue)filter--selected @endif"
                               href="{{request()->fullUrlWithQuery(['available'=>$availabilityValue])}}"> {{$availabilityKey}} </a>
                        </li>
                    @endforeach
                </ol>
            </div>
            <div class="filter--item">
                <h3> Categoria </h3>
                <ol>
                    <li><a class="clickable @if($category==null)filter--selected @endif filter--default"
                           href="{{request()->fullUrlWithQuery(['category'=>null])}}"> Qualsiasi </a></li>
                    @foreach( $categories as $c)
                        <li>
                            <a class="clickable @if($category!=null && $category==$c)filter--selected @endif"
                               href="{{request()->fullUrlWithQuery(['category'=>$c])}}"> {{$c}} </a></li>
                    @endforeach
                </ol>
            </div>
            <div class="filter--item">
                <h3> Tecnica </h3>
                <ol>
                    <li><a class="clickable @if($tool==null)filter--selected @endif filter--default"
                           href="{{request()->fullUrlWithQuery(['tool'=>null])}}"> Qualsiasi </a></li>
                    @foreach( $tools as $t)
                        <li>
                            <a class="clickable @if($tool!=null && $tool==$t)filter--selected @endif"
                               href="{{request()->fullUrlWithQuery(['tool'=>$t])}}"> {{$t}} </a></li>
                    @endforeach
                </ol>
            </div>
        </div>

        {{--Catalogue Grid--}}
        <div style="width: 100%">
            <div class="round_rectangle row"
                 style="margin-bottom: 34px; display: grid; grid-template-columns: auto min-content min-content 10px; column-gap: 12px">
                <input id="search" onkeyup="search(event.key)"
                       placeholder="Nome articolo"
                       value="{{$search_string}}">
                <img class="clickable" style="margin: auto 0;cursor: pointer" width="18px"
                     src="{{asset('images/delete.svg')}}"
                     alt=""
                     onclick="reset()">
                <img class="clickable" style="margin: auto 0;cursor: pointer" width="26px"
                     src="{{asset('images/search.svg')}}"
                     alt=""
                     onclick="search()">
            </div>

            @if($articles->isEmpty())
                <h1 style="width: 100%; opacity: 0.35; text-align: center">Nessun articolo.</h1>
            @else
                <div class="grid_responsive"
                     style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); row-gap: 40px; ">
                    @foreach ($articles as $article)
                        @include('partials.article',['$article'=>$article])
                        @include('partials.article',['$article'=>$article])
                        @include('partials.article',['$article'=>$article])
                    @endforeach
                </div>
            @endif
        </div>

        <script>
            function search(key) {
                if (key == null || key === 'Enter') {
                    const search = document.getElementById('search').value;
                    let url = '{!! request()->fullUrlWithQuery(['search_string'=>'_search_string']) !!}';
                    url = url.replace('_search_string', search);
                    window.location = url;
                }
            }

            function reset() {
                window.location = "{{route('articles.index')}}";
            }

            $(() => {
                // Set the search filter caret
                const input = document.getElementById('search');
                const length = input.value.length;
                input.selectionStart = 0;
                input.selectionEnd = length;
                input.focus();
            });

            // Set the filters hide/show behaviours
            $('.filter--item h3').each((i, element) => {
                if ($(element.parentElement).find('.filter--default').hasClass('filter--selected'))
                    $(element).parent().find('ol').hide()
                $(element).on('click', () => {
                    $(element).parent().toggleClass('hidden');
                    const ol = $(element).parent().find('ol');
                    if (ol.is(':hidden'))
                        ol.show()
                    else
                        ol.hide()
                });
            })

        </script>

@endsection
