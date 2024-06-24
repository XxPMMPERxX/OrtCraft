<template>
  <div>
    <div role="tablist" class="tabs" :class="[tabTypeClasses[type], tabSizeClasses[size]]">
      <a v-for="tabTitle in tabTitles" role="tab" class="tab" :key="tabTitle"
        :class="getTabName(tabTitle) === activeTab ? 'tab-active' : ''" @click="() => activeTab = getTabName(tabTitle)">
        <slot :name="tabTitle"></slot>
      </a>
    </div>

    <div v-for="tabContent in tabContents.filter(slotName => getTabName(slotName) === activeTab)" :key="tabContent">
      <slot :name="tabContent"></slot>
    </div>
  </div>
</template>

<script setup lang="ts">
/**
 * タブコンポーネント
 *   slots
 *     - tabTitle.[tabName]   タブ部分の表示
 *     - tabContent.[tabName] タブのコンテンツの表示
 */
import {
  computed,
  ref,
  useSlots
} from 'vue';

interface Props {
  /**
   * boxed    - 四角いタブのスタイル
   * bordered - 線のタブのスタイル
   * lifted   - ブラウザのタブみたいなスタイル
   */
  type?: 'boxed' | 'bordered' | 'lifted',

  /**
   * タブのサイズ 未指定の場合は md
   */
  size?: 'xs' | 'sm' | 'md' | 'lg',

  /**
   * 初期にアクティブにするタブ名
   * 指定なしの場合は一番目
   */
  default?: string,
};
const props = withDefaults(defineProps<Props>(), {
  type: 'boxed',
  size: 'md',
});
const slots = useSlots();

const tabSizeClasses = {
  xs: 'tabs-xs',
  sm: 'tabs-sm',
  md: 'tabs-md',
  lg: 'tabs-lg',
};
const tabTypeClasses = {
  boxed: 'tabs-boxed',
  bordered: 'tabs-bordererd',
  lifted: 'tabs-lifted',
};

const tabTitles = computed(() => {
  const slotNames = Object.keys(slots).filter(key => {
    return /^tabTitle\..*/.test(key);
  })

  return slotNames;
});

const tabContents = computed(() => {
  const slotNames = Object.keys(slots).filter(key => {
    return /^tabContent\..*/.test(key)
  });

  return slotNames;
});

/**
 * slotの名前からタブ名を取得
 */
function getTabName(slotName: string) {
  return slotName.match(/^(tabTitle|tabContent)\.(?<tabName>.*)/)?.groups?.tabName ?? '';
}

/**
 * 現在アクティブなタブ
 */
const activeTab = ref<string>(props.default ?? getTabName(tabTitles.value[0] ?? ''));

</script>
