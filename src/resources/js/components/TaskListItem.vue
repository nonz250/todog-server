<template>
  <v-list-item
    :class="listClass"
  >
    <v-list-item-action class="mr-2">
      <v-checkbox
        v-model="checked"
        @change="change"
      />
    </v-list-item-action>

    <v-list-item-content>{{ task.name }}</v-list-item-content>

    <v-list-item-action>
      <v-menu
        v-model="detail"
        transition="slide-x-transition"
        absolute
        left
        bottom
      >
        <template v-slot:activator="{ on }">
          <v-scroll-x-transition>
            <v-icon
              v-if="task.status === 1"
              color="success"
            >
              mdi-check
            </v-icon>
          </v-scroll-x-transition>
          <v-btn
            icon
            v-on="on"
          >
            <v-icon>mdi-dots-vertical</v-icon>
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
    </v-list-item-action>

    <v-icon class="handle ml-4">
      mdi-cursor-move
    </v-icon>

    <alert-dialog
      :dialog="dialog"
      :title="title"
      :ok="ok"
      :cancel="cancel"
      @ok="clickDelete"
      @cancel="dialog = false"
    >
      <div v-html="text" />
    </alert-dialog>

    <v-dialog
      v-model="formDialog"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
    >
      <v-card tile>
        <v-toolbar class="grey lighten-4">
          <v-btn
            icon
            @click="formDialog = false"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
          <v-toolbar-title>タスクを編集</v-toolbar-title>
          <v-spacer />
          <v-toolbar-items>
            <v-btn
              text
              @click="clickResetNotificationStartDate"
            >
              通知日時を削除
            </v-btn>
          </v-toolbar-items>
          <v-toolbar-items>
            <v-btn
              text
              @click="clickUpdate"
            >
              更新
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>

        <v-list
          three-line
          subheader
        >
          <v-subheader>タスク情報</v-subheader>
          <v-list-item>
            <v-list-item-content>
              <v-list-item-title>タスク</v-list-item-title>
              <v-list-item-subtitle>
                <v-textarea
                  ref="edit-task"
                  v-model="dialogTaskName"
                  label="タスク"
                  rows="2"
                  maxlength="255"
                  counter
                  full-width
                  auto-grow
                />
              </v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
          <v-list-item>
            <v-list-item-content>
              <v-list-item-title>タスク期限</v-list-item-title>
              <div class="width: 290px;">
                <v-date-picker
                  v-model="limitDate"
                  full-width
                  landscape
                  locale="ja"
                  color="grey darken-1"
                  :day-format="date => new Date(date).getDate()"
                />
              </div>
            </v-list-item-content>
          </v-list-item>
          <v-list-item>
            <v-list-item-content>
              <v-list-item-title>通知日時</v-list-item-title>
              <v-text-field
                v-model="notificationStartDays"
                prefix="期限"
                suffix="日前から通知"
                :rules="[rules.number]"
              />
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-card>
    </v-dialog>
  </v-list-item>
</template>

<script>
import tasks from '../app/tasks';
import dayjs from 'dayjs';
import AlertDialog from './AlertDialog';

export default {
  name: 'TaskListItem',
  components: {AlertDialog},
  props: {
    task: {
      type: Object,
      default: () => {
        return {};
      }
    },
    localClass: {
      type: String,
      default: '',
    }
  },
  data() {
    return {
      dialog: false,
      formDialog: false,
      title: '',
      text: '',
      dialogTaskName: '',
      notificationStartDays: 0,
      ok: {color: 'default', text: ''},
      cancel: {color: 'default', text: 'キャンセル'},
      detail: false,
      operationLists: [
        {
          name: 'タスクを編集',
          color: '',
          operation: 'edit'
        },
        {
          name: 'タスクを削除',
          color: 'error',
          operation: 'delete'
        }
      ],
      rules: {
        required: value => !!value || 'この項目は必須です。',
        number: value => {
          const number = Number(value);
          if (!Number.isInteger(number)) {
            return '数値で入力して下さい。';
          }
          return typeof Number(value) === 'number' || '数値で入力して下さい。';
        },
      },
    };
  },
  computed: {
    limitDate: {
      get() {
        if (this.task.limit_date !== null) {
          return dayjs(this.task.limit_date).format('YYYY-MM-DD');
        }
        return null;
      },
      set(value) {
        this.task.limit_date = dayjs(value).format('YYYY-MM-DD');
      }
    },
    listClass() {
      let result = {};
      result[this.localClass] = true;
      if (this.checked) {
        result['bg-color-checked'] = true;
      }
      result['mr-0'] = true;
      result['pr-0'] = true;
      return result;
    },
    checked: {
      get() {
        return this.task.status === tasks.STATUS_COMPLETED;
      },
      set(value) {
        this.task.status = value ? tasks.STATUS_COMPLETED : tasks.STATUS_DEFAULT;
      }
    }
  },
  watch: {
    task() {
      if (this.limitDate !== null && this.task.notification_start_date !== null) {
        this.notificationStartDays = dayjs(this.limitDate)
          .diff(this.task.notification_start_date, 'day');
      }
    }
  },
  created() {
    if (this.limitDate !== null && this.task.notification_start_date !== null) {
      this.notificationStartDays = dayjs(this.limitDate)
        .diff(this.task.notification_start_date, 'day');
    }
  },
  methods: {
    change() {
      this.task.status = this.checked ? tasks.STATUS_COMPLETED : tasks.STATUS_DEFAULT;
      this.$emit('change', this.task);
    },
    clickOperation(operation) {
      if (operation === 'delete') {
        this.dialog = true;
        this.title = 'タスク削除';
        this.text = 'タスクを削除しますか？<br>この操作は元には戻せません。';
        this.ok.text = '削除';
        this.ok.color = 'error';
      } else if (operation === 'edit') {
        this.dialogTaskName = this.task.name;
        this.formDialog = true;
        const vm = this;
        this.$nextTick(() => {
          vm.$refs['edit-task'].focus();
        });
      } else {
        console.error('この操作はありません。');
      }
    },
    clickUpdate() {
      this.formDialog = false;
      this.task.name = this.dialogTaskName;
      this.task.limit_date = this.limitDate;
      if (this.limitDate !== null && this.notificationStartDays !== null) {
        this.task.notification_start_date = dayjs(this.limitDate)
          .subtract(this.notificationStartDays, 'day')
          .format('YYYY-MM-DD');
      }
      this.$emit('update', this.task);
    },
    clickDelete() {
      this.dialog = false;
      this.$emit('delete', this.task);
    },
    getItemClass(color) {
      return {
        'red--text': color === 'error',
      };
    },
    clickResetNotificationStartDate() {
      this.notificationStartDays = null;
      this.task.notification_start_date = null;
    }
  }
};
</script>

<style scoped>
    .handle {
        padding: 5px;
        border: solid #ccc 1px;
        border-radius: 5px;
    }
    .handle:hover {
        border: solid #000 1px;
        cursor: grab;
    }
    .handle:active {
        cursor: grabbing;
    }
</style>
