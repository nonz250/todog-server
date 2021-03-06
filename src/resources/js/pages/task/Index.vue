<template>
  <ul class="horizontal-list">
    <li
      v-for="(list, index) in lists"
      :key="index"
      class="task-list"
    >
      <task-list
        :task-list="list"
        @clickAddTask="clickAddTask"
        @change="updateTask"
        @delete-list="deleteList"
        @update-task="updateTask"
        @delete-task="deleteTask"
        @archive-tasks="archiveTasks"
        @update-task-list="updateTaskList"
        @change-sort="changeSort"
      />
    </li>
    <li class="task-list">
      <v-btn
        text
        outlined
        block
        class="add-list-container"
        :color="isDisplayInputName ? 'error' : 'primary'"
        @click="clickChangeIsDisplayInputName"
      >
        <span v-show="!isDisplayInputName">
          <v-icon>mdi-plus-circle-outline</v-icon>
          リストを追加する
        </span>
        <span v-show="isDisplayInputName">
          <v-icon>mdi-minus-circle-outline</v-icon>
          キャンセル
        </span>
      </v-btn>
      <v-text-field
        v-show="isDisplayInputName"
        ref="input-task-list"
        v-model="name"
        placeholder="タスクリスト名"
      />
      <v-btn
        v-show="isDisplayInputName"
        color="primary"
        text
        outlined
        class="float-right"
        @click="clickAddList"
      >
        <v-icon>mdi-plus-circle-outline</v-icon>
        追加
      </v-btn>
    </li>
  </ul>
</template>

<script>
import mixin from '../../mixins/mixin';
import dayjs from 'dayjs';
import TaskList from '../../components/TaskList';

export default {
  name: 'Index',
  components: {TaskList},
  mixins: [mixin],
  data() {
    return {
      isDisplayInputName: false,
      name: '',
      sort: 0,
      lists: [],
    };
  },
  async created() {
    await this.getTaskLists();
  },
  methods: {
    click() {
      const loader = this.$store.getters['loader/loader'];
      this.$store.dispatch('loader/setLoader', !loader);
    },
    clickChangeIsDisplayInputName() {
      this.isDisplayInputName = !this.isDisplayInputName;
      const vm = this;
      this.$nextTick(() => {
        vm.$refs['input-task-list'].focus();
      });
    },
    async getTaskLists() {
      await this.$store.dispatch('loader/setLoader', true);

      const params = new URLSearchParams();
      params.append('status', 'enabled');

      const res = await this.api('get', 'api/task_list', params);

      if (res.status === 200) {
        for (let i in res.data) {
          this.lists.push({
            id: res.data[i].id,
            name: res.data[i].name,
            sort: res.data[i].sort,
            tasks: res.data[i].tasks,
          });
        }
      } else if (res.status === 401) {
        await this.$store.dispatch('reloadDialog/setReloadDialog', true);
      } else if (res.status === 422) {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', this.getMessages(res.data.errors));
        await this.$store.dispatch('snackbar/setColor', 'error');
      } else {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', 'サーバーでエラーが発生しました。');
        await this.$store.dispatch('snackbar/setColor', 'error');
      }

      await this.$store.dispatch('loader/setLoader', false);
    },
    async updateTaskList(taskList) {
      await this.$store.dispatch('loader/setLoader', true);

      const params = new FormData();
      params.append('name', taskList.name);
      params.append('sort', taskList.sort);

      const res = await this.api('put', '/api/task_list/' + taskList.id, params);

      if (res.status === 200) {
        for (let i in this.lists) {
          if (Number(res.data.id) === Number(this.lists[i].id)) {
            this.lists[i].name = res.data.name;
          }
        }
      } else if (res.status === 401) {
        await this.$store.dispatch('reloadDialog/setReloadDialog', true);
      } else if (res.status === 422) {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', this.getMessages(res.data.errors));
        await this.$store.dispatch('snackbar/setColor', 'error');
      } else {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', 'サーバーでエラーが発生しました。');
        await this.$store.dispatch('snackbar/setColor', 'error');
      }

      await this.$store.dispatch('loader/setLoader', false);
    },
    async clickAddList() {
      await this.$store.dispatch('loader/setLoader', true);

      const max = this.lists.map((item) => {
        return item.sort;
      });

      const sort = Math.max.apply(null, max) + 1;

      const params = new FormData();
      await params.append('name', this.name);
      await params.append('sort', String(sort));

      const res = await this.api('post', '/api/task_list', params);

      if (res.status === 200) {
        this.lists.push({
          id: res.data.id,
          name: res.data.name,
          sort: res.data.sort,
          tasks: [],
        });
        this.isDisplayInputName = false;
        this.name = null;
      } else if (res.status === 401) {
        await this.$store.dispatch('reloadDialog/setReloadDialog', true);
      } else if (res.status === 422) {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', this.getMessages(res.data.errors));
        await this.$store.dispatch('snackbar/setColor', 'error');
      } else {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', 'サーバーでエラーが発生しました。');
        await this.$store.dispatch('snackbar/setColor', 'error');
      }

      await this.$store.dispatch('loader/setLoader', false);
    },
    async clickAddTask(taskList, task) {
      await this.$store.dispatch('loader/setLoader', true);

      const params = new FormData();
      params.append('task_list_id', taskList.id);
      params.append('name', task.name);

      const res = await this.api('post', '/api/task', params);
      if (res.status === 200) {
        for (let i in this.lists) {
          if (this.lists[i].id === res.data.task_list_id) {
            this.lists[i].tasks.push(res.data);
          }
        }
      } else if (res.status === 401) {
        await this.$store.dispatch('reloadDialog/setReloadDialog', true);
      } else if (res.status === 422) {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', this.getMessages(res.data.errors));
        await this.$store.dispatch('snackbar/setColor', 'error');
      } else {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', 'サーバーでエラーが発生しました。');
        await this.$store.dispatch('snackbar/setColor', 'error');
      }

      await this.$store.dispatch('loader/setLoader', false);
    },
    async updateTask(task) {
      await this.$store.dispatch('loader/setLoader', true);

      const params = new FormData();
      params.append('task_list_id', task.task_list_id);
      params.append('name', task.name);
      console.log(task);
      if (task.limit_date !== null) {
        params.append('limit_date', dayjs(task.limit_date).format('YYYY-MM-DD'));
        if (task.notification_start_date !== null) {
          params.append('notification_start_date', task.notification_start_date);
        }
      }
      params.append('status', task.status);

      const res = await this.api('put', '/api/task/' + task.id, params);

      // eslint-disable-next-line no-empty
      if (res.status === 200) {
      } else if (res.status === 401) {
        await this.$store.dispatch('reloadDialog/setReloadDialog', true);
      } else if (res.status === 422) {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', this.getMessages(res.data.errors));
        await this.$store.dispatch('snackbar/setColor', 'error');
      } else {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', 'サーバーでエラーが発生しました。');
        await this.$store.dispatch('snackbar/setColor', 'error');
      }

      await this.$store.dispatch('loader/setLoader', false);
    },
    async deleteTask(task) {
      await this.$store.dispatch('loader/setLoader', true);

      const params = new FormData();

      const res = await this.api('delete', '/api/task/' + task.id, params);

      if (res.status === 200) {
        const taskId = Number(res.data.id);
        const taskListId = Number(res.data.task_list_id);

        for (let i in this.lists) {
          if (Number(this.lists[i].id) === taskListId) {
            for (let j in this.lists[i].tasks) {
              if (Number(this.lists[i].tasks[j].id) === taskId) {
                this.lists[i].tasks.splice(j, 1);
              }
            }
          }
        }
      } else if (res.status === 401) {
        await this.$store.dispatch('reloadDialog/setReloadDialog', true);
      } else if (res.status === 422) {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', this.getMessages(res.data.errors));
        await this.$store.dispatch('snackbar/setColor', 'error');
      } else {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', 'サーバーでエラーが発生しました。');
        await this.$store.dispatch('snackbar/setColor', 'error');
      }

      await this.$store.dispatch('loader/setLoader', false);
    },
    async deleteList(taskList) {
      await this.$store.dispatch('loader/setLoader', true);

      const params = new FormData();

      const res = await this.api('delete', '/api/task_list/' + taskList.id, params);

      if (res.status === 200) {
        const taskListId = Number(res.data.id);

        for (let i in this.lists) {
          if (Number(this.lists[i].id) === taskListId) {
            this.lists.splice(i, 1);
          }
        }
      } else if (res.status === 401) {
        await this.$store.dispatch('reloadDialog/setReloadDialog', true);
      } else if (res.status === 422) {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', this.getMessages(res.data.errors));
        await this.$store.dispatch('snackbar/setColor', 'error');
      } else {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', 'サーバーでエラーが発生しました。');
        await this.$store.dispatch('snackbar/setColor', 'error');
      }

      await this.$store.dispatch('loader/setLoader', false);
    },
    async archiveTasks(taskIds) {
      await this.$store.dispatch('loader/setLoader', true);

      if (taskIds.length === 0) {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', '完了したタスクがありません。');
        await this.$store.dispatch('snackbar/setColor', 'error');
        await this.$store.dispatch('loader/setLoader', false);
        return;
      }

      const params = new FormData();

      const res = await this.api('post', '/api/archive/task/' + taskIds, params);

      if (res.status === 200) {
        const taskIds = res.data.task_ids;
        for (let i in this.lists) {
          for (let j in this.lists[i].tasks) {
            for (let k in taskIds) {
              if (Number(taskIds[k]) === Number(this.lists[i].tasks[j].id)) {
                this.lists[i].tasks.splice(j, 1);
              }
            }
          }
        }
      } else if (res.status === 401) {
        await this.$store.dispatch('reloadDialog/setReloadDialog', true);
      } else if (res.status === 422) {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', this.getMessages(res.data.errors));
        await this.$store.dispatch('snackbar/setColor', 'error');
      } else {
        await this.$store.dispatch('snackbar/setSnackbar', true);
        await this.$store.dispatch('snackbar/setText', 'サーバーでエラーが発生しました。');
        await this.$store.dispatch('snackbar/setColor', 'error');
      }

      await this.$store.dispatch('loader/setLoader', false);
    },
    async changeSort(taskList) {
      let deleteKey;
      Object.keys(this.lists).forEach((key) => {
        if (Number(this.lists[key].id) === Number(taskList.id)) {
          deleteKey = key;
        }
      });

      this.lists.splice(deleteKey, 1);

      if (this.lists.length < taskList.sort) {
        this.lists.push(taskList);
      } else {
        Object.keys(this.lists).forEach((key) => {
          console.log(Number(key));
          console.log(Number(taskList.sort) - 1);
          console.log(Number(key) === Number(taskList.sort) - 1);
          if (Number(key) === Number(taskList.sort) - 1) {
            this.lists.splice(Number(key), 0, taskList);
          }
        });
      }

      const parallels = [];

      Object.keys(this.lists).forEach((key) => {
        this.lists[key].sort = Number(key) + 1;
        parallels.push(new Promise((resolve) => {
          return resolve(this.updateTaskList(this.lists[key]));
        }));
      });

      await Promise.all(parallels);
    }
  }
};
</script>

<style scoped>
    .horizontal-list {
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        margin: 0;
        padding: 0;
        height: 100%;
    }

    .task-list {
        display: inline-block;
        vertical-align: top;
        margin: 0;
        padding: 1rem;
    }

    .add-list-container {
        border: dashed 2px #000000;
    }

    @media screen and (min-width: 375px) and (max-width: 850px) {
        .task-list {
            width: 90%;
        }
    }

    @media screen and (min-width: 851px) and (max-width: 920px) {
        .task-list {
            width: 60%;
        }
    }

    @media screen and (min-width: 921px) {
        .task-list {
            width: 40%;
        }
    }
</style>
