<template>
  <v-container>
    <v-row>
      <v-col>
        <v-card>
          <v-card-text>
            <p>Android端末の方は下のボタンからインストールできます。</p>
            <p>iPhone端末の方はブラウザの<strong>「ホームに追加」</strong>を利用してください。</p>
          </v-card-text>
          <v-card-actions>
            <v-spacer />
            <v-btn
              text
              outlined
              color="success"
              @click="install"
            >
              インストール
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
    <v-row>
      <v-col>
        <v-card>
          <v-card-text>
            <p>タスクの通知を受け取ることができます。</p>
            <p>通知を受け取りたい場合は「通知を受けとる」を押して、通知を許可してください。</p>
          </v-card-text>
          <v-card-actions>
            <v-spacer />
            <v-btn
              text
              outlined
              color="success"
              @click="notification"
            >
              通知を受けとる
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import mixin from '../mixins/mixin';
import swController from '../src/swController';

const debug = process.env.NODE_ENV !== 'production';

import firebase from 'firebase';

export default {
  name: 'Index',
  mixins: [mixin],
  data() {
    return {
      sw: null,
    };
  },
  computed: {
    debug() {
      return process.env.NODE_ENV !== 'production';
    }
  },
  methods: {
    async install() {
      this.sw = await (new swController()).init();
      await this.sw.installApp();
    },
    async notification() {
      firebase.messaging().requestPermission().then(() => {
        if (debug) {
          console.log('通知許可');
        }
        firebase.messaging().getToken().then(token => {
          if (debug) {
            console.log('==========');
            console.log(token);
            console.log('==========');
          }
          const params = new FormData();
          params.append('token', token);

          const res = this.api('post', '/api/fcm_token', params);

          if (debug) {
            console.log(res);
          }
        });
      });
      firebase.messaging().onMessage((payload) => {
        if (debug) {
          console.log(payload);
        }
      });
    },
  }
};
</script>

<style scoped>

</style>
