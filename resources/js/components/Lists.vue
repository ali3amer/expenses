<template>
    <div>
        <div class="box box-primary">

            <div class="box-body none-print">
                <div class="row">
                    <div class="col-md-4">
                        <select name="" v-model="form.state" :disabled="permissions.power != 'developer'"
                                @change="getTowns" class="form-control">
                            <option value="">إختر الولايه ..........</option>
                            <option v-for="state in states" :value="state.id">{{ state.name }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="" v-model="form.town" @change="getUnits"
                                :disabled="form.state == '' || permissions.power != 'developer'"
                                class="form-control">
                            <option value="">إختر المحليه ..........</option>
                            <option v-for="town in towns" :value="town.id">{{ town.name }}</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="" v-model="form.unit" @change="getZones" :disabled="form.town == ''"
                                class="form-control">
                            <option value="">إختر الوحده ..........</option>
                            <option v-for="unit in units" :value="unit.id">{{ unit.name }}</option>
                        </select>
                    </div>

                    <div class="col-md-1">
                        <button :disabled="Object.keys(number).length == 0 || Object.keys(cost).length == 0" class="btn btn-success btn-block none-print" @click="printPage()"><i class="fa fa-print"></i></button>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
        </div>

        <div class="box box-primary">
            <div class="box box-body">
                <table class="table table-responsive table-bordered none-print">
                    <thead class="bg-black">
                    <tr>
                        <th class="text-center" style="width: 33.3%">المنطقه</th>
                        <th class="text-center" style="width: 33.3%">عدد المستفيدين</th>
                        <th class="text-center" style="width: 33.3%">التكلفه</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="zone in zones">
                        <td class="text-center">{{ zone['name'] }}</td>
                        <td><input type="text" class="form-control text-center" v-model="number[zone.id]"></td>
                        <td><input type="text" class="form-control text-center" v-model="cost[zone.id]"></td>
                    </tr>
                    </tbody>
                    <tfoot class="bg-black" style="text-align: center !important;">
                        <tr>
                            <th class="text-center">الجمــــــــــــــــــــله</th>
                            <th class="text-center">{{ sumCount(zones) }}</th>
                            <th class="text-center">{{ sumCost(zones) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="box-footer">
                <button class="btn btn-primary  none-print" @click="resetList"><i class="fa fa-refresh"></i></button>
            </div>
        </div>
        <!-- /.box -->

        <div class="box box-primary" v-if="Object.keys(number).length != 0">
            <div class="box box-body">

                <div class="text-center" style="border-bottom: 3px double #333; margin-bottom: 10px">
                    <h3>بسم الله الرحمن الرحيم</h3>
                    <h3>ديوان الزكاه الولاية <span>{{ states[form.state].name }}</span></h3>
                    <h3>محلية <span>{{ towns[form.town].name }}</span></h3>
                    <h3><span>وحدة</span> - <span>{{ units[form.unit].name }}</span></h3>
                </div>

                <table class="table table-responsive table-bordered text-center">
                    <thead class="bg-black">
                    <tr>
                        <th class="text-center" style="width: 33.3%">المنطقه</th>
                        <th class="text-center" style="width: 33.3%">عدد المستفيدين</th>
                        <th class="text-center" style="width: 33.3%">التكلفه</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="zone in zones" v-if="parseInt(number[zone.id]) > 0">
                        <td class="text-center">{{ zone['name'] }}</td>
                        <td>{{ number[zone.id] }}</td>
                        <td>{{ cost[zone.id] }}</td>
                    </tr>
                    </tbody>
                    <tfoot class="bg-black" style="text-align: center !important;">
                    <tr>
                        <th class="text-center">الجمــــــــــــــــــــله</th>
                        <th class="text-center">{{ sumCount(zones) }}</th>
                        <th class="text-center">{{ sumCost(zones) }}</th>
                    </tr>
                    </tfoot>
                </table>

                <div v-for="zone in zones" v-if="parseInt(number[zone.id]) > 0">
                    <h3>{{ zone.name }}</h3>
                    <table class="table table-responsive table-bordered">
                        <thead class="bg-black">
                        <tr>
                            <th class="text-center" style="width: 20%">الإسم</th>
                            <th class="text-center" style="width: 20%">الرقم الوطني</th>
                            <th class="text-center" style="width: 20%">الكميه</th>
                            <th class="text-center" style="width: 10%">البصمه</th>
                            <th class="text-center" style="width: 10%">البصمه</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="i in parseInt(number[zone.id])">
                            <td class="text-center"></td>
                            <td style="padding-top: 20px; padding-bottom: 20px"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        </tbody>
                        <tfoot class="bg-black" style="text-align: center !important;">
                        <tr>
                            <th class="text-center">الجمــــــــــــــــــــله</th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                            <th class="text-center" colspan="2"></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.box -->

    </div>
</template>

<script>
export default {
    data() {
        return {
            editMode: false,
            printMode: false,
            modalTitle: 'reports',
            routeTitle: 'report',
            title: 'التقارير',
            subtitle: 'تقرير',
            rows: {},
            states: {},
            towns: {},
            units: {},
            zones: {},
            fields: {},
            supplies: {},
            number: {},
            cost: {},
            form: new Form({
                id: '',
                from: '',
                to: '',
                reportType: '',
                reportCategory: '',
                supportType: '',
                supply: '',
                state: '',
                town: '',
                unit: '',
                zone: ''
            })
        }
    },
    props: ['permissions'],
    methods: {
        printPage() {
            window.print();
        },
        sumCount(zones) {
            let count = 0;
            for (let zone in zones) {
                if (this.number[zone] > 0) {
                    count += parseInt(this.number[zone]);
                }
            }

            return count;
        },
        sumCost(zones) {
            let cost = 0;
            for (let zone in zones) {
                if (this.cost[zone] > 0) {
                    cost += parseInt(this.cost[zone]);
                }

            }
            return cost;
        },
        resetList() {
            this.cost = {};
            this.number = {};
        },
        newModal() {
            this.editMode = false;
            this.form.reset();
        },
        loadData() {
            this.form.post('api/' + this.routeTitle)
                .then(data => {
                    this.rows = data.data;
                });
        },

        getTowns() {
            axios.get('api/state/' + this.form.state).then(({data}) => (this.towns = data));
        },
        getUnits() {
            axios.get('api/town/' + this.form.town).then(({data}) => (this.units = data));
        },
        getZones() {
            if (this.form.unit != '') {
                axios.get('api/unit/' + this.form.unit).then(({data}) => (this.zones = data));
            } else {
                this.zones = {};
            }
        },
    },
    created() {

        if (this.permissions.power == 'town') {
            axios.get('api/user_Power?user=' + this.permissions.id)
                .then(response => {
                    axios.get('api/state?state=all').then(({data}) => (this.states = data));

                    this.form.state = this.permissions.state_id;
                    this.getTowns();
                    this.form.town = response.data;
                    this.getUnits();
                });
        } else {
            axios.get('api/state?state=all').then(({data}) => (this.states = data));
        }
    }
}
</script>
