<template>
    <div>

        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title" style="display: inline-block">{{ title }}</h3>
                <select v-model="town" @change="loadData">
                    <option value="">إختر المحليه........</option>
                    <option v-for="(town, index) in towns" :key="town.id" :value="town.id">{{ town.name }}</option>
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
                        <th>الولايه</th>
                        <th>التحكم</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in rows.data" :key="row.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.town.name }}</td>
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
                                <input v-model="form.name" type="text" name="name" placeholder="إسم الوحده الإداريه"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <select name="town_id" v-model="form.town_id" :class="{ 'is-invalid': form.errors.has('town_id') }"
                                        class="form-control">
                                    <option value="">إختر المحليه ........</option>
                                    <option v-for="(town, index) in towns" :key="town.id" :value="town.id">{{
                                            town.name }}
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
            modalTitle: 'units',
            routeTitle: 'unit',
            title: 'الوحدات',
            subtitle: 'الوحده',
            rows: {},
            towns: {},
            town: '',
            form: new Form({
                id: '',
                name: '',
                town_id: ''
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
            if (this.town == '') {
                axios.get('api/' + this.routeTitle).then(({data}) => (this.rows = data));
            } else {
                axios.get('api/' + this.routeTitle + '?town=' + this.town).then(({data}) => (this.rows = data));
            }
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
        }
    },
    created() {

        axios.get('api/town?town=all').then(({data}) => (this.towns = data));

        this.loadData();

    }
}
</script>

