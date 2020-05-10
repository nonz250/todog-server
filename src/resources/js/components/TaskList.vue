<template>
  <v-card
    :color="color"
  >
    <v-card-title>
      <v-row no-gutters>
        <v-col cols="10">
          <span v-show="!isEditTaskList">{{ taskList.name }}</span>
          <v-text-field
            v-show="isEditTaskList"
            ref="input-task-list"
            v-model="inputTaskListName"
            placeholder="タスクリスト名"
          />
          <v-btn
            v-show="isEditTaskList"
            text
            outlined
            color="success"
            class="float-right"
            @click="clickUpdateTaskList"
          >
            <v-icon>mdi-plus-circle-outline</v-icon>
            更新
          </v-btn>
          <v-btn
            v-show="isEditTaskList"
            text
            outlined
            class="float-right"
            @click="isEditTaskList = false"
          >
            <v-icon>mdi-minus-circle-outline</v-icon>
            キャンセル
          </v-btn>
        </v-col>

        <v-col
          class="my-0 py-0"
          cols="2"
        >
          <v-menu
            v-show="isAdd === false"
            v-model="detail"
            transition="slide-x-transition"
            absolute
            left
            bottom
          >
            <template v-slot:activator="{ on }">
              <v-btn
                v-show="isAdd === false"
                text
                icon
                class="float-right"
                v-on="on"
              >
                <v-icon>mdi-dots-horizontal</v-icon>
              </v-btn>
            </template>

            <v-list>
              <v-list-item
                v-for="(operation, index) in operationLists"
                :key="index"
                @click="clickOperation(operation.operation)"
              >
                <v-list-item-title :class="getItemClass(operation.color)">
                  {{ operation.name }}
                </v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-col>
      </v-row>
    </v-card-title>

    <v-divider />

    <v-card-text>
      <v-list
        v-show="isColorPicker === true"
      >
        <v-row
          justify="space-around"
        >
          <v-color-picker
            v-model="color"
            disabled
            hide-canvas
            hide-inputs
            hide-mode-switch
            show-swatches
            flat
            :swatches="[
              ['#CFD8DC', '#FFCDD2', '#F0F4C3'],
              ['#E8EAF6', '#FBE9E7', '#EFEBE9'],
              ['#E3F2FD', '#FFE0B2'],
              ['#E0F7FA', '#FFECB3'],
              ['#E8F5E9', '#FFF9C4'],
            ]"
          />
        </v-row>
      </v-list>
      <v-list
        v-show="isColorPicker === false"
        :color="color"
      >
        <draggable
          v-model="taskList.tasks"
          group="group"
          v-bind="{animation: 200, handle: '.handle', delay: 200}"
          @add="dragEnd"
          @end="test"
        >
          <div
            v-for="(task, index) in taskList.tasks"
            :key="index"
          >
            <task-list-item
              :color="color"
              :task="task"
              local-class="draggable-item"
              @change="change"
              @update="updateTask"
              @delete="deleteTask"
            />

            <v-divider />
          </div>
        </draggable>
      </v-list>

      <div
        v-show="isColorPicker === false"
        class="text-right"
      >
        <v-btn
          text
          outlined
          :color="isAdd ? 'error' : 'primary'"
          @click="clickEnabledTaskForm"
        >
          <v-icon v-show="!isAdd">
            mdi-plus-circle-outline
          </v-icon>
          <span v-show="!isAdd">タスクを追加する</span>

          <v-icon v-show="isAdd">
            mdi-minus-circle-outline
          </v-icon>
          <span v-show="isAdd">キャンセル</span>
        </v-btn>
      </div>

      <div
        v-show="isColorPicker === true"
        class="text-right"
      >
        <v-btn
          text
          outlined
          color="default"
          @click="isColorPicker = false"
        >
          <v-icon>mdi-minus-circle-outline</v-icon>
          キャンセル
        </v-btn>
        <v-btn
          text
          outlined
          color="success"
          @click="resetColorPicker"
        >
          <v-icon>mdi-close-circle-outline</v-icon>
          リセット
        </v-btn>
        <v-btn
          text
          outlined
          color="primary"
          @click="clickColorPicker"
        >
          <v-icon>mdi-plus-circle-outline</v-icon>
          色を決定する
        </v-btn>
      </div>
    </v-card-text>

    <v-card-actions v-show="isAdd">
      <v-textarea
        ref="input-task"
        v-model="taskName"
        class="float-left"
        label="タスク"
        rows="2"
        maxlength="255"
        counter
        full-width
        auto-grow
      />

      <v-btn
        color="success"
        class="float-left"
        text
        outlined
        @click="clickAddTask"
      >
        <v-icon>mdi-plus-circle-outline</v-icon>
        追加
      </v-btn>
    </v-card-actions>

    <alert-dialog
      :dialog="dialog"
      :title="title"
      :ok="ok"
      :cancel="cancel"
      :disabled="!isExistCompletedTasks"
      @ok="archiveTasks"
      @cancel="dialog = false"
    >
      <div v-html="text" />
    </alert-dialog>

    <v-form
      ref="form"
      v-model="valid"
      lazy-validation
    >
      <alert-dialog
        :dialog="isSort"
        :disabled="!valid"
        title="リストの順番を変更"
        @ok="changeSort"
        @cancel="isSort = false"
      >
        <v-container>
          <v-text-field
            ref="sort"
            v-model="sort"
            prefix="リストを"
            suffix="番目に移動する"
            :rules="[rules.unsigned_int]"
          />
        </v-container>
      </alert-dialog>
    </v-form>
  </v-card>
</template>

<script>
import draggable from 'vuedraggable';
import tasks from '../app/tasks';
import AlertDialog from './AlertDialog';
import TaskListItem from './TaskListItem';

export default {
  name: 'TaskList',
  components: {AlertDialog, TaskListItem, draggable},
  props: {
    taskList: {
      type: Object,
      required: true,
      default: () => {
        return {};
      },
    }
  },
  data() {
    return {
      isAdd: false,
      isEditTaskList: false,
      dialog: false,
      isColorPicker: false,
      isSort: false,
      color: '#FFFFFF',
      title: '',
      text: '',
      ok: {color: 'default', text: ''},
      cancel: {color: 'default', text: 'キャンセル'},
      detail: false,
      taskName: '',
      operationLists: [
        {
          name: 'リスト名を変更',
          color: 'default',
          operation: 'changeTaskListName',
        },
        {
          name: 'リストの色を変更',
          color: 'default',
          operation: 'color',
        },
        {
          name: 'リストの順番を変更',
          color: 'default',
          operation: 'sort',
        },
        {
          name: '完了タスクを削除',
          color: 'error',
          operation: 'archive',
        },
        {
          name: 'リストを削除',
          color: 'error',
          operation: 'delete',
        },
      ],
      valid: true,
      rules: {
        required: value => !!value || 'この項目は必須です。',
        unsigned_int: value => {
          const number = Number(value);
          if (!Number.isInteger(number)) {
            return '数値で入力して下さい。';
          }
          if (typeof Number(value) !== 'number') {
            return '数値で入力して下さい。';
          }
          if (number <= 0) {
            return '1以上で入力して下さい。';
          }
          return true;
        },
      },
    };
  },
  computed: {
    isExistCompletedTasks() {
      const completedTasks = [];
      this.taskList.tasks.forEach((item) => {
        if (item.status === tasks.STATUS_COMPLETED) {
          completedTasks.push(item);
        }
      });
      return completedTasks.length > 0;
    },
    inputTaskListName: {
      get() {
        return this.taskList.name;
      },
      set(value) {
        this.taskList.name = value;
      }
    },
    sort: {
      get() {
        return this.taskList.sort;
      },
      set(value) {
        this.taskList.sort = value;
      }
    }
  },
  watch: {
    taskList() {
      const color = localStorage.getItem('task_list_color:' + this.taskList.id);
      this.color = color === null ? '#FFFFFF' : color;
    }
  },
  created() {
    const color = localStorage.getItem('task_list_color:' + this.taskList.id);
    this.color = color === null ? '#FFFFFF' : color;
  },
  methods: {
    clickAddTask() {
      this.isAdd = false;
      const task = {
        id: null,
        name: this.taskName,
      };
      this.taskName = '';
      this.$emit('clickAddTask', this.taskList, task);
    },
    clickEnabledTaskForm() {
      this.isAdd = !this.isAdd;
      const vm = this;
      this.$nextTick(() => {
        vm.$refs['input-task'].focus();
      });
    },
    clickOperation(operation) {
      if (operation === 'delete') {
        this.$emit('delete-list', this.taskList);
      } else if (operation === 'archive') {
        this.title = '完了タスクを削除';
        this.text = '完了タスクを削除します。<br>削除されたタスクはゴミ箱で確認できます。';
        this.ok.text = '削除';
        this.ok.color = 'error';
        this.dialog = true;
      } else if (operation === 'color') {
        this.isColorPicker = true;
      } else if (operation === 'sort') {
        this.isSort = true;
        const vm = this;
        this.$nextTick(() => {
          vm.$refs['sort'].focus();
        });
      } else if (operation === 'changeTaskListName') {
        this.isEditTaskList = true;
      } else {
        console.error('この操作はありません。');
      }
    },
    change(task) {
      this.$emit('change', task);
    },
    updateTask(task) {
      this.$emit('update-task', task);
    },
    deleteTask(task) {
      this.$emit('delete-task', task);
    },
    archiveTasks() {
      this.dialog = false;
      const taskIds = [];
      this.taskList.tasks.forEach((item) => {
        if (item.status === tasks.STATUS_COMPLETED) {
          taskIds.push(item.id);
        }
      });
      this.$emit('archive-tasks', taskIds);
    },
    getItemClass(color) {
      return {
        'red--text': color === 'error',
      };
    },
    clickColorPicker() {
      this.isColorPicker = false;
      localStorage.setItem('task_list_color:' + this.taskList.id, this.color);
    },
    resetColorPicker() {
      this.color = '#FFFFFF';
      localStorage.setItem('task_list_color:' + this.taskList.id, this.color);
    },
    clickUpdateTaskList() {
      this.taskList.name = this.inputTaskListName;
      this.isEditTaskList = false;
      this.$emit('update-task-list', this.taskList);
    },
    changeSort() {
      this.isSort = false;
      this.$emit('change-sort', this.taskList);
    },
    dragEnd() {
      let task = null;

      Object.keys(this.taskList.tasks).forEach((key) => {
        if (Number(this.taskList.tasks[key].task_list_id) !== Number(this.taskList.id)) {
          this.taskList.tasks[key].task_list_id = this.taskList.id;
          task = this.taskList.tasks[key];
        }
      });

      if (task !== null) {
        this.$emit('update-task', task);
      }
    },
    test() {
      console.log('test');
    }
  }
};
</script>

<style scoped>
    .bg-color-checked {
        background-color: #f5f5f5;
    }
    .sortable-chosen {
        opacity: 0.7;
        background-color:#dcdcdc;
    }
    .sortable-ghost {
        background-color:#dcdcdc;
    }
</style>
