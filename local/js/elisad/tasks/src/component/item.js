import './item.css';

export const Item = {
    props: { position: { default: 0 }, text: { default: '' } },
    template: `
      <div class="taskmanager-list-item">{{position}}. {{text}}</div>
    `
};
