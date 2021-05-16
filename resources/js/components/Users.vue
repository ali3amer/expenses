<template>
    <div>

        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">{{ title }}</h3>

                <div class="box-tools">
                    <button class="btn btn-primary" data-toggle="modal" @click="newModal()"
                            :data-target="'#' + modalTitle"><i class="fa fa-plus"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive p-0">
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>الرقم</th>
                        <th>الاسم</th>
                        <th>التحكم</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in rows.data" :key="row.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.name }}</td>
                        <td>
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

        <div class="modal fade" :id="modalTitle" tabindex="-1" role="dialog" aria-labelledby="addNewLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <form @submit.prevent="editMode ? updateData() : createData()">
                        <div class="modal-header">
                            <h4 class="modal-title" style="display:inline-block" v-show="editMode" id="addNewLabel1">
                                تعديل بيانات {{ subtitle }}</h4>
                            <h4 class="modal-title" style="display:inline-block" v-show="!editMode" id="addNewLabel">
                                إضافة {{ subtitle }} جديد</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" placeholder="إسم المستخدم"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.password" type="password" name="password" placeholder="كلمة السر"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
                            </div>

                            <div class="form-group">
                                <select v-model="form.power" class="form-control" :class="{ 'is-invalid': form.errors.has('power') }">
                                    <option value="">إختر الصلاحيه ....................</option>
                                    <option value="developer" v-if="permissions.power == 'developer'">مبرمج</option>
                                    <option value="admin" v-if="permissions.power == 'developer'">مدير نظام</option>
                                    <option value="town">مستخدم محليه</option>
                                </select>
                                <has-error :form="form" field="power"></has-error>
                            </div>

                            <div class="form-group">
                                <select v-model="form.state_id" disabled class="form-control" :class="{ 'is-invalid': form.errors.has('state_id') }">
                                    <option value="">إختر الولايه ....................</option>
                                    <option v-for="state in states" :value="state.id">{{ state.name }}</option>
                                </select>
                                <has-error :form="form" field="state_id"></has-error>
                            </div>

                            <div class="form-group" v-if="form.power == 'town'">
                                <select v-model="form.town_id" class="form-control" :class="{ 'is-invalid': form.errors.has('town_id') }">
                                    <option value="">إختر المحليه ....................</option>
                                    <option v-for="town in towns" :value="town.id">{{ town.name }}</option>
                                </select>
                                <has-error :form="form" field="town_id"></has-error>
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

    </div>
</template>

<script>
    export default {
        data() {
            return {
                editMode: false,
                modalTitle: 'users',
                routeTitle: 'user',
                title: 'المستخدمين',
                subtitle: 'مستخدم',
                rows: {},
                state: '',
                states: {},
                towns: {},
                form: new Form({
                    id: '',
                    name: '',
                    password: '',
                    state_id: '',
                    town_id: '',
                    power: ''
                })
            }
        },
        props:['permissions'],
        methods: {
            getResults(page = 1) {
                axios.get('api/'+ this.routeTitle + '?page=' + page)
                    .then(response => {
                        this.rows = response.data;
                    });
            },
            updateData() {
                this.$Progress.start();
                this.form.put('api/'+ this.routeTitle + '/' + this.form.id).then(() => {
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
                this.form.state_id = this.permissions.state_id;
            },
            editModal(row) {
                this.editMode = true;
                this.form.reset();
                $("#" + this.modalTitle).modal('show');
                this.form.fill(row);
                this.form.town_id = row.user_power.town_id;
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
                axios.get('api/' + this.routeTitle).then(({data}) => (this.rows = data));
            },
            createData() {
                this.$Progress.start();
                $("#" + this.modalTitle).modal('hide');
                this.form
                    .post("api/" + this.routeTitle)
                    .then(() => {
                        // Fire.$emit("afterCreate");
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
                axios.get('api/state/' + this.permissions.state_id).then(({data}) => (this.towns = data));
            },
        },
        created() {

            this.loadData();

            axios.get('api/state?state=all').then(({data}) => (this.states = data));

            this.state = this.permissions.state_id;


            this.getTowns();

        }
    }
</script>
