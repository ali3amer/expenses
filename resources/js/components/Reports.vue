<template>
    <div>

        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="row">
                            <label for="from" class="col-sm-2 col-form-label">من</label>
                            <div class="col-sm-10">
                                <input type="date" v-model="form.from" class="form-control" id="from">
                            </div>
                        </div>
                        <br>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <label for="to" class="col-sm-2 col-form-label">الى</label>
                            <div class="col-sm-10">
                                <input type="date" v-model="form.to" :disabled="form.from == ''" class="form-control"
                                       id="to">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select v-model="form.reportType" class="form-control">
                            <option value="">إختر نوع التقرير ..........</option>
                            <option value="state" v-if="permissions.power != 'town'">ولايه</option>
                            <option value="town">محليه</option>
                            <option value="unit">وحده اداريه</option>
                            <option value="zone">منطقه</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select v-model="form.reportCategory" class="form-control">
                            <option value="">إختر تصنيف التقرير ..........</option>
                            <option value="1">عام</option>
                            <option value="2">تفصيلي</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select v-model="form.supportType" class="form-control">
                            <option value="">إختر نوع الدعم ..........</option>
                            <option value="1">نقدي</option>
                            <option value="2">عيني</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2" v-if="form.supportType == 2">
                        <select name="" v-model="form.supply" class="form-control">
                            <option value="">إختر العيني ..........</option>
                            <option v-for="supply in supplies" :value="supply.id">{{ supply.name }}</option>
                        </select>
                    </div>

                    <div :class="form.supportType == 1 || form.supportType == '' ? 'col-md-3' : 'col-md-2'">
                        <select name="" v-model="form.state" :disabled="permissions.power != 'developer'" @change="getTowns" class="form-control">
                            <option value="">إختر الولايه ..........</option>
                            <option v-for="state in states" :value="state.id">{{ state.name }}</option>
                        </select>
                    </div>
                    <div :class="form.supportType == 1 || form.supportType == '' ? 'col-md-3' : 'col-md-2'">
                        <select name="" v-model="form.town" @change="getUnits" :disabled="form.state == '' || permissions.power != 'developer' || form.reportType == 'state'"
                                class="form-control">
                            <option value="">إختر المحليه ..........</option>
                            <option v-for="town in towns" :value="town.id">{{ town.name }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="" v-model="form.unit" @change="getZones" :disabled="form.town == '' || form.reportType == 'state' || form.reportType == 'town'"
                                class="form-control">
                            <option value="">إختر الوحده ..........</option>
                            <option v-for="unit in units" :value="unit.id">{{ unit.name }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="" v-model="form.zone" :disabled="form.unit == '' || form.reportType != 'zone'" class="form-control">
                            <option value="">إختر المنطقه ..........</option>
                            <option v-for="zone in zones" :value="zone.id">{{ zone.name }}</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary btn-block" @click="loadData"><i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
        </div>

        <div class="box box-primary">
            <div class="box box-body">
                <table class="table table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 33.3%">المصرف</th>
                        <th style="width: 33.3%">المستفيدين</th>
                        <th style="width: 33.3%">التكلفه</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="table in tables">
                        <td>{{ table.name }}</td>
                        <td>{{ sumCount(rows[table.id]) }}</td>
                        <td>{{ sumCost(rows[table.id]) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box box-primary" v-for="table in tables">
            <div class="box-header">
                <h3 class="box-title">{{ table.name }}</h3>
            </div>
            <div class="box box-body">
                <table class="table table-responsive table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 33.3%">البند</th>
                        <th style="width: 33.3%">المستفيدين</th>
                        <th style="width: 33.3%">التكلفه</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in rows[table.id]">
                        <td>{{ fields[index].name }}</td>
                        <td>{{ row.count }}</td>
                        <td>{{ row.cost }}</td>
                    </tr>
                    </tbody>
                </table>
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
            modalTitle: 'reports',
            routeTitle: 'report',
            title: 'التقارير',
            subtitle: 'تقرير',
            rows: {},
            states: {},
            towns: {},
            units: {},
            zones: {},
            tables: {},
            fields: {},
            supplies: {},
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
        sumCount(rows) {
            let count = 0;
            for (let row in rows) {
                count += rows[row].count;
            }
            return count;
        },
        sumCost(rows) {
            let cost = 0;
            for (let row in rows) {
                cost += rows[row].cost;
            }
            return cost;
        },
        getResults(page = 1) {
            axios.get('api/' + this.routeTitle + '?page=' + page)
                .then(response => {
                    this.rows = response.data;
                });
        },
        updateData() {
            this.$Progress.start();
            this.form.put('api/' + this.routeTitle + '/' + this.form.id).then(() => {
                // Fire.$emit('afterCreate');

                $("#" + this.modalTitle).modal('hide');
                this.loadData();
                toast.fire({
                    icon: 'success',
                    title: 'تم التعديل بنجاح'
                });

                this.$Progress.finish();
            })
                .catch(() => {
                    toast.fire({
                        icon: "error",
                        title: "لم يتم التعديل"
                    });
                });
        },
        newModal() {
            this.editMode = false;
            this.form.reset();
        },
        editModal(row) {
            this.editMode = true;
            this.form.reset();
            $("#" + this.modalTitle).modal('show');
            this.form.fill(row);
        },
        deleteData(id) {
            swal.fire({
                title: 'هل أنت متأكد أنك تريد الحذف',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'لا',
                confirmButtonText: 'نعم'
            }).then((result) => {
                this.$Progress.start();
                if (result.value) {
                    this.form.delete('api/' + this.routeTitle + '/' + id).then(() => {
                        this.loadData();
                        this.$Progress.finish();
                        toast.fire({
                            icon: 'success',
                            title: 'تم الحذف بنجاح'
                        });
                        // Fire.$emit('afterCreate');
                    }).catch(() => {
                        toast.fire({
                            icon: "error",
                            title: "لم يتم الحذف"
                        });
                    });

                }
            });
        },
        loadData() {
            this.form.post('api/' + this.routeTitle)
                .then(data => {
                    this.rows = data.data;
                });
        },
        createData() {
            this.$Progress.start();
            $("#" + this.modalTitle).modal('hide');
            this.form
                .post("api/" + this.routeTitle)
                .then(() => {
                    $("#" + this.modalTitle).modal("hide");
                    toast.fire({
                        icon: "success",
                        title: "تم الحفظ بنجاح"
                    });
                    this.loadData();
                    this.$Progress.finish();
                })
                .catch(() => {
                    toast.fire({
                        icon: "Failed",
                        title: "لم يتم الحفظ "
                    });
                });
        },
        getTowns() {
            axios.get('api/state/' + this.form.state).then(({data}) => (this.towns = data));
        },
        getUnits() {
            axios.get('api/town/' + this.form.town).then(({data}) => (this.units = data));
        },
        getZones() {
            axios.get('api/unit/' + this.form.unit).then(({data}) => (this.zones = data));
        },
        getFields() {
            axios.get('api/table/' + this.table).then(({data}) => (this.fields = data));
        }
    },
    created() {

        axios.get('api/table?select=all').then(({data}) => (this.tables = data));
        axios.get('api/field?select=all').then(({data}) => (this.fields = data));
        axios.get('api/supply?select=all').then(({data}) => (this.supplies = data));

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
