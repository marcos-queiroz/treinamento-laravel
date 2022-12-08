<template>
    <table class="table table-hover">
        <thead>
            <tr>
                <th v-for="t, key in title" :key="key">
                    {{ t.title }}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="obj, i in filteredData" :key="i">
                <td v-for="value, key in obj" :key="key">
                    <span v-if="title[key].type === 'string'">
                        {{ value }}
                    </span>
                    <span v-if="title[key].type === 'date'">
                        {{ value | formatDate }}
                    </span>
                    <span v-if="title[key].type === 'image'">
                        <img :src="`/storage/${value}`" alt="Logo" width="30">
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: ['title', 'data'],
    computed: {
        filteredData() {
            let fields = Object.keys(this.title);
            let newData = [];

            this.data.map((i, key) => {

                let filteredItem = {};

                fields.forEach((field) => {
                    filteredItem[field] = i[field];
                });

                newData.push(filteredItem);
            });

            return newData;
        }
    }
}
</script>
