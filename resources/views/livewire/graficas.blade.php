@if(Auth::user()->hasRole('comer'))
    {{-- Gráficas para el rol "comer" --}}
    <div class="flex">
        {{-- <div class="border border-gray-300 bg-white rounded w-1/3 ml-4 mr-4 mt-8">
            {!! $chart1_comer->container() !!}
            <script src="{{ $chart1_comer->cdn() }}"></script>
            {{ $chart1_comer->script() }}
        </div> --}}

        <div class="border border-gray-300 bg-white rounded w-1/2 mr-4 mt-8">
            {!! $chart2_comer->container() !!}
            <script src="{{ $chart2_comer->cdn() }}"></script>
            {{ $chart2_comer->script() }}
        </div>

        <div class="border border-gray-300 bg-white rounded w-1/2 mr-4 mt-8">
            {!! $chart3_comer->container() !!}
            <script src="{{ $chart3_comer->cdn() }}"></script>
            {{ $chart3_comer->script() }}
        </div>
    </div>
@elseif(Auth::user()->hasRole('produ'))

@elseif(Auth::user()->hasRole('disen'))

@else   
    {{-- Gráficas para los roles "admin" y "contab" --}}
    <div class="flex">
        <div class="border border-gray-300 bg-white rounded w-1/3 ml-4 mr-4 mt-8">
            {!! $chart1->container() !!}
            <script src="{{ $chart1->cdn() }}"></script>
            {{ $chart1->script() }}
        </div>

        <div class="border border-gray-300 bg-white rounded w-1/3 mr-4 mt-8">
            {!! $chart2->container() !!}
            <script src="{{ $chart2->cdn() }}"></script>
            {{ $chart2->script() }}
        </div>

        <div class="border border-gray-300 bg-white rounded w-1/3 mr-4 mt-8">
            {!! $chart3->container() !!}
            <script src="{{ $chart3->cdn() }}"></script>
            {{ $chart3->script() }}
        </div>
    </div>

    <div class="flex">
        <div class="border border-gray-300 bg-white rounded w-2/3 ml-6 mr-8 mt-8 ">
            {!! $chart4->container() !!}
            <script src="{{ $chart4->cdn() }}"></script>
            {{ $chart4->script() }}
        </div>

        <div class="border border-gray-300 bg-white rounded w-1/3 mr-4 mt-8 ">
            {!! $chart5->container() !!}
            <script src="{{ $chart5->cdn() }}"></script>
            {{ $chart5->script() }}
        </div>
    </div>
@endif


