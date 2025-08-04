class CustomCharts {
    constructor() {
        this.init();
    }

    async init() {
        await this.loadChartJS();
        this.renderCharts();
    }

    loadChartJS() {
        return new Promise(resolve => {
            if (window.Chart) {
                resolve();
                return;
            }

            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            script.onload = resolve;
            document.head.appendChild(script);
        });
    }

    renderCharts() {
        // Implementación similar a la anterior
    }
}

new CustomCharts();