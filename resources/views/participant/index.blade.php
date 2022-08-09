@extends('layouts.app')

@section('content')
<div id="app" class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div>
                <b-card no-body>
                    <b-tabs card>
                        <b-tab title="{{ __('Participants') }}" active>
                            <b-card-text><app-data-table name="participants" url="api/participants" v-bind:filter="true" v-bind:columns="columns" v-on:item-clicked="participantClicked"></app-data-table></b-card-text>
                        </b-tab>
                        <b-tab title="Tab 2" v-for="(tab, index) in tabs" v-bind:key="index">
                            <b-card-text>Tab contents 2</b-card-text>
                        </b-tab>
                    </b-tabs>
                </b-card>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function () {

        const app = new Vue({
            el: '#app',
            data: {
                columns: [{
                    title: '',
                    checkbox: true,
                    checkboxEnabled: true,
                }, {
                    title: 'ID',
                    field: 'id',
                    switchable: false,
                    visible: false,
                }, {
                    title: 'Name',
                    field: 'name',
                    sortable: true,
                    switchable: false,
                }, {
                    title: 'Firstname',
                    field: 'firstname',
                    sortable: true,
                }, {
                    title: 'Email',
                    field: 'email',
                    sortable: true,
                    formatter: APP.dataTable.formatters.emailFormatter,
                }, {
                    title: 'Gender',
                    field: 'gender',
                    sortable: true,
                    formatter: APP.dataTable.formatters.genderFormatter,
                    searchFormatter: false,
                }, {
                    title: 'Birthdate',
                    field: 'birthdate',
                    sortable: true,
                    formatter: APP.dataTable.formatters.dateFormatter,
                }],
                tabs: [],
            },
            computed: {},
            methods: {
                participantClicked(participant) {
                    console.log('Participant clicked', participant);
                }
            },
            mounted() {},
        });

    });
</script>
@endsection
