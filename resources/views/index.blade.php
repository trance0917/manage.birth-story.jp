@extends('layout')
@section('meta')@endsection
@section('contents')
    <h1>ダッシュボード</h1>
    <div class="em-container">
        <section class="em-board" style-scope="r-hecttf">
            <h2 class="em-board-head">レポート</h2>
            <div class="em-board-content">
                <table>
                    <tr>
                        <th></th><th>見積</th><th>受注</th><th>売上</th><th>利益</th>
                    </tr>
                    <tr class="text-right">
                        <th>本日</th><td class="w-20">50</td><td class="w-20">30(60%)</td><td class="w-22">1,000,000</td><td class="w-30">300,000(30%)</td>
                    </tr>
                    <tr class="text-right">
                        <th>昨日</th><td>50</td><td>30(60%)</td><td>1,000,000</td><td>300,000(30%)</td>
                    </tr>
                    <tr class="text-right">
                        <th>今週</th><td>50</td><td>30(60%)</td><td>1,000,000</td><td>300,000(30%)</td>
                    </tr>
                    <tr class="text-right">
                        <th>今月</th><td>50</td><td>30(60%)</td><td>1,000,000</td><td>300,000(30%)</td>
                    </tr>
                    <tr class="text-right">
                        <th>前週比</th><td>50</td><td>30(60%)</td><td>1,000,000</td><td>300,000(30%)</td>
                    </tr>
                    <tr class="text-right">
                        <th>前月比</th><td>50</td><td>30(60%)</td><td>1,000,000</td><td>300,000(30%)</td>
                    </tr>
                </table>
                <style>
                    [style-scope=r-hecttf] table tr th,
                    [style-scope=r-hecttf] table tr td{
                        padding: 5px 3px 5px 3px;
                    }
                </style>
            </div>

        </section>
    </div><!--end em-container-->

@endsection

@section('javascript')@endsection
