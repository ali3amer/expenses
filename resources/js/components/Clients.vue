<template>
    <div>

        <div class="row">
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{ title }}</h3>
                        <select v-model="state" :disabled="permissions.power != 'developer'" @change="getTowns">
                            <option value="">إختر الولايه........</option>
                            <option v-for="(state, index) in states" :key="state.id" :value="state.id">{{ state.name }}</option>
                        </select>

                        <select v-model="town" :disabled="permissions.power != 'developer' || state == ''" @change="getUnits">
                            <option value="">إختر المحليه........</option>
                            <option v-for="(town, index) in towns" :key="town.id" :value="town.id">{{ town.name }}</option>
                        </select>

                        <select v-model="unit" :disabled="town == ''" @change="getZones">
                            <option value="">إختر الوحده........</option>
                            <option v-for="(unit, index) in units" :key="unit.id" :value="unit.id">{{ unit.name }}</option>
                        </select>

                        <select v-model="zone" :disabled="unit == ''" @change="loadData(); support_form.zone_id = zone;">
                            <option value="">إختر المنطقه........</option>
                            <option v-for="(zone, index) in zones" :key="zone.id" :value="zone.id">{{ zone.name }}</option>
                        </select>

                        <input type="text" :disabled="zone == ''" v-model="search" style="width: 100px" @keyup="loadData" placeholder="الإسم او الرقم الوطني">

                        <div class="box-tools">
                            <button class="btn btn-primary" :disabled="zone == ''" data-toggle="modal" @click="newModal()"
                                    :data-target="'#' + modalTitle"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive p-0">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>الرقم الوطني</th>
                                <th>الإسم</th>
                                <th>الهاتف</th>
                                <th>التحكم</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in rows.data" :key="row.id">
                                <td>{{ row.nID }}</td>
                                <td>{{ row.name }}</td>
                                <td>{{ row.phone }}</td>
                                <td>
                                    <a href="#" data-target="#support" @click="newSupportModal(row)"><i
                                        class="fa fa-plus"></i></a> /
                                    <a href="#" @click="loadSupportData(row)"><i
                                        class="fa fa-eye"></i></a> /
                                    <a href="#" :data-target="'#' + modalTitle" @click="editModal(row)"><i
                                        class="fa fa-edit blue"></i></a> / <a href="#" @click="deleteData(row.id)"><i
                                    class="fa fa-trash red"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <pagination :data="rows" @pagination-change-page="getResults"></pagination>

                    </div>
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><span>الدعم النقدي ل:</span><span>{{ client_name }}</span></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive p-0">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>المبلغ</th>
                                <th>التاريخ</th>
                                <th>التحكم</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in cashes.data" :key="row.id">
                                <td>{{ formatPrice(row.amount) }}</td>
                                <td>{{ row.created_at }}</td>
                                <td>
                                    <a href="#" data-target="#support" @click="editSupportModal(row, 1)"><i
                                        class="fa fa-edit blue"></i></a> / <a href="#" @click="deleteSupportData(row.id, 'cash')"><i
                                    class="fa fa-trash red"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <pagination :data="cashes" @pagination-change-page="getCashResults"></pagination>

                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title"><span>الدعم العيني ل:</span><span>{{ client_name }}</span></h3>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive p-0">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>الكميه</th>
                                <th>الدعم</th>
                                <th>التاريخ</th>
                                <th>التحكم</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in crops.data" :key="row.id">
                                <td>{{ row.quantity }}</td>
                                <td>{{ supplies[row.crop_id].weight}} {{ supplies[row.crop_id].name }}</td>
                                <td>{{ row.created_at }}</td>
                                <td>
                                    <a href="#" data-target="#support" @click="editSupportModal(row, 2)"><i
                                        class="fa fa-edit blue"></i></a> / <a href="#" @click="deleteSupportData(row.id, 'crop')"><i
                                    class="fa fa-trash red"></i></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <pagination :data="crops" @pagination-change-page="getCropResults"></pagination>

                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>

        <div class="modal fade" :id="modalTitle" tabindex="-1" role="dialog" aria-labelledby="addNewLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <form @submit.prevent="editMode ? updateData() : createData()">
                        <div class="modal-header">
                            <h4 class="modal-title" style="display:inline-block" v-show="editMode" id="addNewLabel1">
                                تعديل بيانات {{ subtitle }}</h4>
                            <h4 class="modal-title" style="display:inline-block" v-show="!editMode" id="addNewLabel">
                                إضافة {{ subtitle }} جديده</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="form-group">
                                <input v-model="form.name" type="text" placeholder="الإسم"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input v-model="form.nID" type="text" placeholder="الرقم الوطني"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('nID') }">
                                    <has-error :form="form" field="nID"></has-error>
                                </div>

                                <div class="form-group col-md-6">
                                    <input v-model="form.age" type="date" placeholder="العمر"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('age') }">
                                    <has-error :form="form" field="age"></has-error>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <select v-model="form.gender" class="form-control"
                                            :class="{ 'is-invalid': form.errors.has('gender') }">
                                        <option value="">إختر النوع............</option>
                                        <option value="male">ذكر</option>
                                        <option value="female">أنثى</option>
                                    </select>
                                    <has-error :form="form" field="gender"></has-error>
                                </div>

                                <div class="form-group col-md-6">
                                    <input v-model="form.health" type="text" placeholder="الحاله الصحيه"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('health') }">
                                    <has-error :form="form" field="health"></has-error>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input v-model="form.phone" type="text" placeholder="رقم التلفون"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                                    <has-error :form="form" field="phone"></has-error>
                                </div>

                                <div class="form-group col-md-6">
                                    <input v-model="form.work" type="text" placeholder="العمل"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('work') }">
                                    <has-error :form="form" field="work"></has-error>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input v-model="form.income" type="text" placeholder="الدخل"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('income') }">
                                    <has-error :form="form" field="income"></has-error>
                                </div>

                                <div class="form-group col-md-6">
                                    <input v-model="form.status" type="text" placeholder="الحاله الاجتماعيه"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('status') }">
                                    <has-error :form="form" field="status"></has-error>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <input v-model="form.numberFamily" type="text" name="name"
                                           placeholder="عدد افراد الاسره"
                                           class="form-control"
                                           :class="{ 'is-invalid': form.errors.has('numberFamily') }">
                                    <has-error :form="form" field="numberFamily"></has-error>
                                </div>

                                <div class="form-group col-md-6">
                                    <input type="text" disabled v-if="zone != ''" :value="zones[zone].name" class="form-control"
                                           :class="{ 'is-invalid': form.errors.has('zone_id') }">
                                    <has-error :form="form" field="zone_id"></has-error>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" v-show="editMode" class="btn btn-success">تعديل</button>
                            <button type="submit" v-show="!editMode" class="btn btn-primary">إضافه</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">إغلاق</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

        <div class="modal fade" id="support" tabindex="-1" role="dialog" aria-labelledby="addNewLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <form @submit.prevent="!editSupportMode ? createSupportData() : updateSupportData()">
                        <div class="modal-header">
                            <h4 class="modal-title" style="display:inline-block" id="addNewLabe">
                                {{ client_name }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="row" style="margin-bottom: 10px">
                                <div class="col-md-4">
                                    <input v-model="support_form.date" class="form-control" type="date">
                                </div>
                                <div class="col-md-4">
                                    <select v-model="table" @change="getFields" class="form-control">
                                        <option value="">المصرف........</option>
                                        <option v-for="table in tables" :value="table.id">{{ table.name }}</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <select v-model="support_form.field_id" :disabled="table == ''" class="form-control">
                                        <option value="">البند........</option>
                                        <option v-for="field in fields" :value="field.id">{{ field.name }}</option>
                                    </select>
                                    <has-error :form="support_form" field="crop_id"></has-error>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <select v-model="support_form.type" class="form-control">
                                        <option value="">نوع الدعم........</option>
                                        <option value="1">نقدي</option>
                                        <option value="2">عيني</option>
                                    </select>
                                    <has-error :form="support_form" field="type"></has-error>
                                </div>

                                <div class="col-md-3" v-if="support_form.type == 2 && support_form.type != ''">
                                    <select v-model="support_form.crop_id" class="form-control">
                                        <option value="">العينيات........</option>
                                        <option v-for="supply in supplies" :value="supply.id">{{ supply.name }}</option>
                                    </select>
                                    <has-error :form="support_form" field="crop_id"></has-error>
                                </div>

                                <div class="col-md-3" v-if="support_form.type != ''">
                                    <input type="text" v-model="support_form.quantity" :placeholder="support_form.type == 2 ? 'الكميه' : 'المبلغ'" class="form-control">
                                    <has-error :form="support_form" field="quantity"></has-error>
                                </div>
                                <div class="col-md-2" v-if="support_form.type == 2 && support_form.type != '' && support_form.crop_id != ''">
                                    <input type="text" :value="supplies[support_form.crop_id].weight" disabled :placeholder="support_form.type == 2 ? 'الكميه' : 'المبلغ'" class="form-control">
                                    <has-error :form="support_form" field="quantity"></has-error>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" v-show="editSupportMode" class="btn btn-success">تعديل</button>
                            <button type="submit" v-show="!editSupportMode" class="btn btn-primary">إضافه</button>
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
            editMode: false,
            editSupportMode: false,
            modalTitle: 'clients',
            routeTitle: 'client',
            title: 'المستفيدين',
            subtitle: 'مستفيد',
            search: '',
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
            form: new Form({
                id: '',
                name: '',
                nID: '',
                age: '',
                gender: '',
                health: '',
                phone: '',
                work: '',
                income: '',
                status: '',
                numberFamily: '',
                zone_id: '',
            }),
            support_form: new Form({
                id: '',
                type: '',
                crop_id: '',
                quantity: '',
                clients: {},
                field_id: '',
                user_id: this.user_id,
                zone_id: '',
                date: '',
                group: false
            }),
        }
    },
    props: ['permissions'],
    methods: {
        formatPrice(value) {
            let val = (value / 1).toFixed(2).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        getResults(page = 1) {
            axios.get('api/' + this.routeTitle + '?page=' + page)
                .then(response => {
                    this.rows = response.data;
                });
        },
        getCashResults(page = 1) {
            axios.get('api/cash?client_id=' + this.current_client.id + '&page=' + page)
                .then(response => {
                    this.cashes = response.data;
                });
        },
        getCropResults(page = 1) {
            axios.get('api/crop?client_id=' + this.current_client.id + '&page=' + page)
                .then(response => {
                    this.crops = response.data;
                });
        },
        createSupportData() {
            this.$Progress.start();
            $("#support, #supportAll").modal('hide');
            let route = '';
            if (this.support_form.type == 1) {
                route = 'cash';
            } else if (this.support_form.type == 2) {
                route = 'crop';
            }
            this.support_form
                .post("api/" + route)
                .then(() => {
                    $("#support").modal("hide");
                    toast.fire({
                        icon: "success",
                        title: "تم الحفظ بنجاح"
                    });
                    this.loadSupportData(this.current_client);
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
            Vue.set(this.support_form.clients, row.id, row);
            this.support_form.group = false;
            $('#support').modal('show');
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
        updateSupportData() {
            this.$Progress.start();

            let route = '';
            if (this.support_form.type == 1) {
                route = 'cash';
            } else if (this.support_form.type == 2) {
                route = 'crop';
            }

            this.support_form.put('api/' + route + '/' + this.support_form.id).then(() => {
                $("#support").modal('hide');
                this.loadSupportData(this.current_client);
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
            this.form.zone_id = this.zone;
        },
        editModal(row) {
            this.editMode = true;
            this.form.reset();
            $("#" + this.modalTitle).modal('show');
            this.form.fill(row);
        },
        editSupportModal(row, type) {
            this.editSupportMode = true;
            this.support_form.reset();
            this.support_form.id = row.id;
            this.support_form.type = type;
            this.support_form.client_id = row.client_id;
            this.support_form.field_id = row.field_id;
            this.support_form.zone_id = row.zone_id;
            this.support_form.date = moment(row.created_at).format('YYYY-MM-DD');
            if (type == 1) {
                this.support_form.quantity = row.amount;
            } else if (type == 2) {
                this.support_form.crop_id = row.crop_id;
                this.support_form.quantity = row.quantity;
            }
            this.table = this.fields[row.field_id].table_id;
            this.getFields();
            $("#support").modal('show');
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
            axios.get('api/' + this.routeTitle + '?zone_id=' + this.zone + '&search=' + this.search).then(({data}) => (this.rows = data));
        },
        loadSupportData(row) {
            this.client_name = row.name;
            this.current_client = row;

            axios.get('api/cash?client_id=' + row.id).then(({data}) => (this.cashes = data));

            axios.get('api/crop?client_id=' + row.id).then(({data}) => (this.crops = data));
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
                    this.loadData();
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
            axios.get('api/unit/' + this.unit).then(({data}) => (this.zones = data));
        },
        getFields() {
            axios.get('api/table/' + this.table).then(({data}) => (this.fields = data));
        }
    },
    created() {


        axios.get('api/table?select=all').then(({data}) => (this.tables = data));
        axios.get('api/field?select=all').then(({data}) => (this.fields = data));

        axios.get('api/supply?select=all').then(({data}) => (this.supplies = data));

        this.support_form.date = moment(new Date()).format('YYYY-MM-DD');

        console.log(this.permissions);

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
