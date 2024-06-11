import { Item } from './item';
import './task-list.css';

export const TaskList = {
	components: { Item },
	data() {
		return {
			list: []
		}
	},
	methods:
	{
		addNew() {
			const result = prompt('Какую задачу вы планируете выполнить?');
			this.list.push(result);
		},
		close()
		{
			this.$Bitrix.Application.get().detachTemplate();
		}
	},
	template: `
		<div class="taskmanager-list">
			<div class="taskmanager-list-title">Список задач на сегодня</div>
			<template v-for="(value, index) in list" :key="index">
				<Item :position="index+1" :text="value"/>
			</template>
			<template v-if="list.length <= 0">
			  	<div class="taskmanager-list-empty">На сегодня задач нет</div>
			</template>
			<div class="taskmanager-list-buttons">
				<button @click="addNew">Добавить задачу</button>
				<button @click="close">Закрыть компонент</button>
			</div>
		</div>
	`
};