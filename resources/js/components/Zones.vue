<template>
    <div>

        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title" style="display: inline-block">{{ title }}</h3>
                <select v-model="state" @change="getTowns">
                    <option value="">إختر الولايه........</option>
                    <option v-for="(state, index) in states" :key="state.id" :value="state.id">{{ state.name }}</option>
                </select>

                <select v-model="town" @change="getUnits">
                    <option value="">إختر المحليه........</option>
                    <option v-for="(town, index) in towns" :key="town.id" :value="town.id">{{ town.name }}</option>
                </select>

                <select v-model="unit" @change="loadData">
                    <option value="">إختر الوحده........</option>
                    <option v-for="(unit, index) in units" :key="unit.id" :value="unit.id">{{ unit.name }}</option>
                </select>


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
                        <th>إسم المحليه</th>
                        <th>الوحده الاداريه</th>
                        <th>التحكم</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in rows.data" :key="row.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.unit.name }}</td>
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
                                إضافة {{ subtitle }} جديده</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" placeholder="إسم المنطقه"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <select name="unit_id" v-model="form.unit_id"
                                        :class="{ 'is-invalid': form.errors.has('town_id') }"
                                        class="form-control">
                                    <option value="">إختر الوحده ........</option>
                                    <option v-for="(unit, index) in units" :key="unit.id" :value="unit.id">{{
                                            unit.name
                                        }}
                                    </option>
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
            modalTitle: 'zones',
            routeTitle: 'zone',
            title: 'المناطق',
            subtitle: 'الوحده',
            rows: {},
            states: {},
            state: '',
            towns: {},
            town: '',
            units: {},
            unit: '',
            form: new Form({
                id: '',
                name: '',
                unit_id: ''
            })
        }
    },
    props: ['id'],
    methods: {
        getResults(page = 1) {
            axios.get('api/' + this.routeTitle + '?unit=' + this.unit + '&page=' + page)
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
            axios.get('api/' + this.routeTitle + '?unit=' + this.unit).then(({data}) => (this.rows = data));
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

        axios.get('api/state?state=all').then(({data}) => (this.states = data));


    }
}
</script>

