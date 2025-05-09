<template>
  <EditorContent
    :editor="editor"
  />
</template>

<script setup>
import { EditorContent, useEditor } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Image from '@tiptap/extension-image';
import Link from '@tiptap/extension-link';
import '@/axios';
import axios from '@/axios';

const model = defineModel();
const editor = useEditor({
  content: model.value,
  extensions: [
    StarterKit,
    Image,
    Link.configure({
      HTMLAttributes: {
        class: 'not-prose link link-primary'
      }
    }),
  ],
  editorProps: {
    attributes: {
      class: 'prose prose-li:my-0 prose-p:my-0 prose-sm sm:prose lg:prose-lg xl:prose-2xl mx-auto focus:outline-none',
    },
  },
  onUpdate: () => {
    model.value = editor.value.isEmpty ? '' : editor.value.getHTML();
  },
  onDrop: async (ev) => {
    ev.preventDefault();

    const files = ev.dataTransfer.files ?? [];

    if (!files.length) {
      return;
    }

    const formData = new FormData();
    formData.append('attachment', files[0])

    const response = await axios.post('/api/upload', formData);

    editor.value.chain().focus().setImage({
      src: response.data,
    }).run();
  },
});
</script>
