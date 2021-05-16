<template>
    <div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-2">
                                <select v-model="support_form.year" class="form-control">
                                    <option value="">إختر العام ........</option>
                                    <option v-for="n in 30" v-if="n >= 20" :value="'20' + n">20{{ n }}</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select v-model="support_form.type" @change="getPrograms" class="form-control">
                                    <option value="">إختر نوع الدعم ...</option>
                                    <option value="1">نقدي</option>
                                    <option value="2">عيني</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select class="form-control" :disabled="support_form.type == ''" v-model="support_form.program">
                                    <option value="">إختر البرنامج........</option>
                                    <option v-for="(row, index) in programs" :key="row.id" :value="row.id">{{
                                        row.name
                                        }}
                                    </option>
                                </select>

                            </div>

                            <div class="col-md-2">
                                <select v-model="state"  :disabled="permissions.power != 'developer'" class="form-control" @change="getTowns">
                                    <option value="">إختر الولايه........</option>
                                    <option v-for="(state, index) in states" :key="state.id" :value="state.id">
                                        {{ state.name }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select class="form-control" :disabled="permissions.power != 'developer'" v-model="town" @change="getUnits">
                                    <option value="">إختر المحليه........</option>
                                    <option v-for="(town, index) in towns" :key="town.id" :value="town.id">{{
                                        town.name
                                        }}
                                    </option>
                                </select>

                            </div>

                            <div class="col-md-2">
                                <select v-model="unit" :disabled="town == '' || support_form.type == ''" class="form-control" @change="getZones">
                                    <option value="">إختر الوحده........</option>
                                    <option v-for="(unit, index) in units" :key="unit.id" :value="unit.id">{{
                                        unit.name
                                        }}
                                    </option>
                                </select>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div v-if="support_form.type == 1 && Object.keys(rows).length != 0" class="box box-primary">
                    <div class="box-header">

                        <h4>برامج الدعم النقدي</h4>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive p-0">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>إسم المنطقه</th>
                                <th>عدد المسنفيدين</th>
                                <th>المبلغ</th>
                                <th>التحكم</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="Object.keys(zones).length != 0" v-for="(row, index) in rows" :key="row.id">
                                <td>{{ zones[index].name }}</td>
                                <td>{{ Object.keys(row).length }}</td>
                                <td>{{ sumCost(row) }}</td>
                                <td class="text-center">
                                    <a href="#" data-toggle="modal" v-if="Object.keys(row).length == 0"
                                       @click="newAllModal(index)"
                                       data-target="#supportAll"><i
                                        class="fa fa-plus"></i></a> <span v-if="Object.keys(row).length == 0"> / </span>
                                    <a href="#" @click="cashRows = row"><i
                                        class="fa fa-eye"></i></a> <span v-if="Object.keys(row).length != 0"> / </span>
                                    <a href="#" :data-target="'#' + modalTitle" v-if="Object.keys(row).length != 0"
                                       @click="support_form.zone_id = index; editSupportModal(row, index)"><i
                                        class="fa fa-edit blue"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">

                    </div>
                </div>
                <!-- /.box -->

                <div v-if="support_form.type == 2 && Object.keys(rows).length != 0" class="box box-primary">
                    <div class="box-header">

                        <h4>برامج الدعم النقدي</h4>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive p-0">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>إسم المنطقه</th>
                                <th>عدد المسنفيدين</th>
                                <th>الكميه</th>
                                <th>التحكم</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-if="Object.keys(zones).length != 0" v-for="(row, index) in rows" :key="row.id">
                                <td>{{ zones[index].name }}</td>
                                <td>{{ Object.keys(row).length }}</td>
                                <td>{{ sumCost(row) }}</td>
                                <td class="text-center">
                                    <a href="#" data-toggle="modal" v-if="Object.keys(row).length == 0"
                                       @click="newAllModal(index)"
                                       data-target="#supportAll"><i
                                        class="fa fa-plus"></i></a> <span v-if="Object.keys(row).length == 0"> / </span>
                                    <a href="#" @click="cropRows = row"><i
                                        class="fa fa-eye"></i></a> <span v-if="Object.keys(row).length != 0"> / </span>
                                    <a href="#" :data-target="'#' + modalTitle" v-if="Object.keys(row).length != 0"
                                       @click="support_form.zone_id = index; editSupportModal(row, index)"><i
                                        class="fa fa-edit blue"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">

                    </div>
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-6">
                <div class="box box-primary" v-if="Object.keys(cashRows).length != 0">
                    <div class="box-header">
                        <h3 class="box-title"><span>الدعم النقدي ل:</span><span></span></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive p-0">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>الرقم الوطني</th>
                                <th>الاسم</th>
                                <th>المبلغ</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in cashRows" :key="row.id">
                                <td>{{ row.client.nID }}</td>
                                <td>{{ row.client.name }}</td>
                                <td>{{ row.amount }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">

                    </div>
                </div>

                <div class="box box-primary" v-if="Object.keys(cropRows).length != 0">
                    <div class="box-header">
                        <h3 class="box-title"><span>الدعم العيني ل:</span><span></span></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive p-0">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>الرقم الوطني</th>
                                <th>الاسم</th>
                                <th>الكميه</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in cropRows" :key="row.id">
                                <td>{{ row.client.nID }}</td>
                                <td>{{ row.client.name }}</td>
                                <td>{{ row.quantity }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">

                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>


        <div class="modal fade" id="supportAll" tabindex="-1" role="dialog" aria-labelledby="addNewLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">

                <div class="modal-content">
                    <form @submit.prevent="editSupportMode ? updateSupportData() : createSupportData()">
                        <div class="modal-header">
                            <div class="row" style="margin-bottom: 10px; display: inline-block;">
                                <div class="col-md-2">
                                    <input type="date" v-model="support_form.date" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <select v-model="table" @change="getFields" class="form-control">
                                        <option value="">المصرف........</option>
                                        <option v-for="table in tables" :value="table.id">{{ table.name }}</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select v-model="support_form.field_id" :disabled="table == ''"
                                            class="form-control">
                                        <option value="">البند........</option>
                                        <option v-for="field in fields" :value="field.id">{{ field.name }}</option>
                                    </select>
                                    <has-error :form="support_form" field="crop_id"></has-error>
                                </div>


                                <div class="col-md-2">
                                    <select disabled v-model="support_form.type" class="form-control">
                                        <option value="">نوع الدعم........</option>
                                        <option value="1">نقدي</option>
                                        <option value="2">عيني</option>
                                    </select>
                                    <has-error :form="support_form" field="type"></has-error>
                                </div>

                                <div class="col-md-2" v-if="support_form.type == 2 && support_form.type != ''">
                                    <select v-model="support_form.crop_id" class="form-control">
                                        <option value="">العينيات........</option>
                                        <option v-for="supply in supplies" :value="supply.id">{{ supply.name }}</option>
                                    </select>
                                    <has-error :form="support_form" field="crop_id"></has-error>
                                </div>

                                <div class="col-md-2" v-if="support_form.type != ''">
                                    <input type="text" v-model="support_form.quantity"
                                           :placeholder="support_form.type == 2 ? 'الكميه' : 'المبلغ'"
                                           class="form-control">
                                    <has-error :form="support_form" field="quantity"></has-error>
                                </div>
                                <div class="col-md-2"
                                     v-if="support_form.type == 2 && support_form.type != '' && support_form.crop_id != ''">
                                    <input type="text" :value="supplies[support_form.crop_id].weight" disabled
                                           style="margin-top: 10px;"
                                           class="form-control">
                                    <has-error :form="support_form" field="quantity"></has-error>
                                </div>
                            </div>

                        </div>
                        <div class="modal-body" style="max-height: 600px; overflow: auto;">


                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th style="width: 30%">الرقم الوطني</th>
                                            <th>الإسم</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, index) in allRows" @click="putList(index)"
                                            style="cursor: pointer"
                                            v-if="!support_form.clients[index]">
                                            <td>{{ allRows[index].nID }}</td>
                                            <td>{{ allRows[index].name }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div
                                    style="position:absolute;height:100%; left: 50%; border-left: 2px solid #333"></div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th style="width: 30%">الرقم الوطني</th>
                                            <th>الإسم</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, index) in support_form.clients"
                                            v-if="Object.keys(allRows).length != 0" style="cursor: pointer"
                                            @click="deleteList(index)">
                                            <td>{{ allRows[index].nID }}</td>
                                            <td>{{ allRows[index].name }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">إضافه</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">إغلاق</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>
</template>

<script>
import moment from "moment";

export default {
    data() {
        return {
            editSupportMode: false,
            modalTitle: 'programs',
            routeTitle: 'program',
            title: 'المستفيدين',
            subtitle: 'مستفيد',
            search: '',
            n: 20,
            rows: {},
            listRows: {},
            visits: {},
            states: {},
            state: '',
            towns: {},
            town: '',
            units: {},
            unit: '',
            zones: {},
            zone: '',
            supplies: {},
            weight: '',
            client_name: '',
            current_client: {},
            tables: {},
            table: '',
            fields: {},
            cashes: {},
            crops: {},
            refresh: '',
            allRows: {},
            programs: {},
            program: '',
            cashRows: {},
            cropRows: {},
            ids: {},
            support_form: new Form({
                id: '',
                year: '',
                crop_id: '',
                quantity: '',
                clients: {},
                ids: {},
                field_id: '',
                user_id: this.user_id,
                zone_id: '',
                date: '',
                type: '',
                program: '',
                group: false,
                editSupportMode: false
            }),
        }
    },
    props: ['permissions'],
    methods: {
        formatPrice(value) {
            let val = (value / 1).toFixed(2).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        sumCost(row) {
            let cost = 0;
            if (this.support_form.type == 1) {
                for (let item in row) {
                    cost += row[item].amount;
                }
            } else if (this.support_form.type == 2) {
                for (let item in row) {
                    cost += row[item].quantity;
                }
            }
            return cost;
        },
        putList(index) {
            // this.support_form.clients.push(Object.assign({}, row));
            // this.ids[row.id] = row.id;
            // this.support_form.clients[row.id] = row.id;
            // this.refresh = 'a';
            // this.refresh = '';
            Vue.set(this.support_form.clients, index, index);
        },
        deleteList(index) {
            this.$delete(this.support_form.clients, index);
        },
        editSupportModal(row, index) {
            this.support_form.clients = {};
            this.editSupportMode = true;
            this.support_form.editSupportMode = true;

            axios.get('api/client?select=all&zone_id=' + this.support_form.zone_id + '&search=' + this.search)
                .then(response => {

                    this.allRows = response.data;
                });

            let route = '';
            if (this.support_form.type == 1) {
                route = 'cash';
            } else if (this.support_form.type == 2) {
                route = 'crop';
            }


            axios.get('api/' + route + '?editMode=edit&zone_id=' + this.support_form.zone_id + '&program_id=' + this.support_form.program)
                .then(response => {
                    this.support_form.clients  = response.data.clients;
                    this.ids = {};
                    for (let item in response.data.clients) {
                        Vue.set(this.ids, item, item);
                    }
                    this.support_form.clients = Object.assign({}, this.ids);
                    this.support_form.field_id = response.data.cashes.field_id;
                    this.support_form.date = moment(response.data.cashes.created_at).format('YYYY-MM-DD');

                    if (this.support_form.type == 1) {
                        this.support_form.quantity = response.data.cashes.amount;
                    } else if (this.support_form.type == 2) {
                        this.support_form.crop_id = response.data.cashes.crop_id;
                        this.support_form.quantity = response.data.cashes.quantity;
                    }
                    this.table = this.fields[response.data.cashes.field_id].table_id;
                    this.support_form.field_id = response.data.cashes.field_id;
                    this.getFields();

                });
            $("#supportAll").modal('show');
        },
        newAllModal(index) {
            this.editSupportMode = false;
            this.support_form.editSupportMode = false;
            this.support_form.zone_id = index;
            this.support_form.clients = {};
            this.support_form.group = true;
            this.support_form.year = moment(new Date()).format('YYYY');
            let route = '';
            if (this.support_form.type == 1) {
                route = 'cash';
            } else if (this.support_form.type == 2) {
                route = 'crop';
            }
            // axios.get('api/' + route + '?zone_id=' + this.support_form.zone_id + '&program_id=' + this.support_form.program).then(({data}) => (this.support_form.clients = data));
            axios.get('api/client?select=all&zone_id=' + this.support_form.zone_id + '&search=' + this.search).then(({data}) => (this.allRows = data));
        },
        createSupportData() {
            this.$Progress.start();
            $("#supportAll").modal('hide');
            let route = '';
            if (this.support_form.type == 1) {
                route = 'cash';
            } else if (this.support_form.type == 2) {
                route = 'crop';
            }
            this.support_form
                .post("api/" + route)
                .then(() => {
                    $("#supportAll").modal("hide");
                    toast.fire({
                        icon: "success",
                        title: "تم الحفظ بنجاح"
                    });
                    this.getZones();
                    this.$Progress.finish();
                })
                .catch(() => {
                    toast.fire({
                        icon: "error",
                        title: "لم يتم الحفظ "
                    });
                });
        },
        newSupportModal(row) {
            this.client_name = row.name;
            this.current_client = row;
            this.support_form.clients = {};
            Vue.set(this.support_form.clients, row.id, row.id);
            this.support_form.group = false;
            $('#supportAll').modal('show');
        },
        updateSupportData() {
            this.$Progress.start();
            $("#supportAll").modal('hide');
            let route = '';
            if (this.support_form.type == 1) {
                route = 'cash';
            } else if (this.support_form.type == 2) {
                route = 'crop';
            }
            this.support_form
                .post("api/" + route)
                .then(() => {
                    $("#supportAll").modal("hide");
                    toast.fire({
                        icon: "success",
                        title: "تم التعديل بنجاح"
                    });
                    this.getZones();

                    this.$Progress.finish();
                })
                .catch(() => {
                    toast.fire({
                        icon: "error",
                        title: "لم يتم التعديل "
                    });
                });
        },
        deleteSupportData(id, name) {
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
                    this.support_form.delete('api/' + name + '/' + id).then(() => {
                        this.loadSupportData(this.current_client);
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
            axios.get('api/?year=' + this.support_form.year).then(({data}) => (this.rows = data));
        },
        loadSupportData(row) {
            this.cashRows = row;
            // this.client_name = row.name;
            // this.current_client = row;
            //
            // axios.get('api/cash?client_id=' + row.id).then(({data}) => (this.cashes = data));
            //
            // axios.get('api/crop?client_id=' + row.id).then(({data}) => (this.crops = data));
        },
        createData() {
            this.$Progress.start();
            $("#" + this.modalTitle).modal('hide');
            this.form
                .post("api/" + this.routeTitle)
                .then(() => {
                    toast.fire({
                        icon: "success",
                        title: "تم الحفظ بنجاح"
                    });
                    this.getZones();
                    this.$Progress.finish();
                })
                .catch(() => {
                    toast.fire({
                        icon: "error",
                        title: "لم يتم الحفظ "
                    });
                });
        },
        getTowns() {
            axios.get('api/state/' + this.state).then(({data}) => (this.towns = data));
        },
        getUnits() {
            axios.get('api/town/' + this.town).then(({data}) => (this.units = data));
        },
        getZones() {
            axios.get('api/unit/' + this.unit + '?program=' + this.support_form.program).then(({data}) => (this.rows = data));
        },
        getFields() {
            axios.get('api/table/' + this.table).then(({data}) => (this.fields = data));
        },
        getPrograms() {
            axios.get('api/program?year=' + this.support_form.year + '&type=' + this.support_form.type).then(({data}) => (this.programs = data));
        }
    },
    created() {

        axios.get('api/state?state=all').then(({data}) => (this.states = data));
        axios.get('api/zone?select=all').then(({data}) => (this.zones = data));

        axios.get('api/table?select=all').then(({data}) => (this.tables = data));
        axios.get('api/field?select=all').then(({data}) => (this.fields = data));

        axios.get('api/supply?select=all').then(({data}) => (this.supplies = data));

        this.support_form.year = moment(new Date()).format('YYYY');
        if (this.permissions.power == 'town') {
            axios.get('api/user_Power?user=' + this.permissions.id)
                .then(response => {
                    axios.get('api/state?state=all').then(({data}) => (this.states = data));

                    this.state = this.permissions.state_id;
                    this.getTowns();
                    this.town = response.data;
                    this.getUnits();
                });
        } else {
            axios.get('api/state?state=all').then(({data}) => (this.states = data));
        }

    }
}
</script>
