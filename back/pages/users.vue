<script setup lang="ts">
</script>

<template>
    <v-row>
      <v-col cols="12"  >
          <TemplatesCRUDTemplate
              :headers="[
                  {title: $t('site.id'), value: 'id'},
                  {title: $t('site.email'), value: 'email'},
                  {title: $t('site.role'), value: 'role'},
                  {title: $t('site.created_at'), value: 'created_at'},
                  {title: $t('site.updated_at'), value: 'updated_at'},
              ]"
              :routes="{
                  index: 'users',
                  create: 'users.update',
                  read: 'users.store',
                  update: 'users.update',
                  delete: 'users.destroy',
              }"
          >
          </TemplatesCRUDTemplate>
      </v-col>
    </v-row>
</template>

<style scoped>

</style>
