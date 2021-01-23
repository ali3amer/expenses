<template>
    <div>

<!--        <div v-if="!$gate.isAdminOrAuthor()">-->
<!--            <not-found></not-found>-->
<!--        </div>-->
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{ title }}</h3>

                        <div class="box-tools">
                            <button class="btn btn-primary" data-toggle="modal" @click="newModal()" :data-target="'#' + modalTitle"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body table-responsive p-0">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>الرقم</th>
                                <th>إسم الولايه</th>
                                <th>التحكم</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in rows.data" :key="row.id">
                                <td>{{ index + 1 }}</td>
                                <td>{{ row.name }}</td>
                                <td>
                                    <a href="#" :data-target="'#' + modalTitle" @click="editModal(row)"><i class="fa fa-edit blue"></i></a> / <a href="#" @click="deleteData(row.id)"><i class="fa fa-trash red"></i></a>
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

        <div class="modal fade" :id="modalTitle" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

                <div class="modal-content">
                    <form @submit.prevent="editMode ? updateData() : createData()">
                        <div class="modal-header">
                            <h4 class="modal-title" style="display:inline-block" v-show="editMode" id="addNewLabel1">تعديل بيانات {{ subtitle }}</h4>
                            <h4 class="modal-title" style="display:inline-block" v-show="!editMode" id="addNewLabel">إضافة {{ subtitle }} جديده</h4>
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
                                    <select v-model="form.gender" class="form-control" :class="{ 'is-invalid': form.errors.has('gender') }">
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
                                    <input v-model="form.numberFamily" type="text" name="name" placeholder="عدد افراد الاسره"
                                           class="form-control" :class="{ 'is-invalid': form.errors.has('numberFamily') }">
                                    <has-error :form="form" field="numberFamily"></has-error>
                                </div>

                                <div class="form-group col-md-6">
                                    <select v-model="form.zone_id" class="form-control" :class="{ 'is-invalid': form.errors.has('zone_id') }">
                                        <option value="">إختر النوع............</option>
                                        <option v-for="zone in zones" :value="zone.id">{{ zone.name }}</option>
                                    </select>
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

    </div>
</template>

<script>
export default {
    data() {
        return {
            editMode: false,
            modalTitle: 'clients',
            routeTitle: 'client',
            title: 'العملاء',
            subtitle: 'عميل',
            rows: {},
            zones: {},
            form: new Form({
                id: '',
                name: '',
                nID:  '',
                age:  '',
                gender:  '',
                health:  '',
                phone:  '',
                work:  '',
                income:  '',
                status:  '',
                numberFamily: '',
                zone_id:  '',
            })
        }
    },
    props:['id'],
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
                    swal("Failed", "There Was Something Wrong.", "warning");
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
                        swal.fire("Failed", "There Was Something Wrong.", "warning");
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
        axios.get('api/state/' + this.state).then(({data}) => (this.towns = data));
    },
    getUnits() {
        axios.get('api/town/' + this.town).then(({data}) => (this.units = data));
    }
    },
    created() {

        this.loadData();

        axios.get('api/zone?zone=all').then(({data}) => (this.zones = data));

    }
}
</script>
