/**
 * chartSetup.js
 *
 * Single source of truth for Chart.js registration.
 * Import this file ONCE at the top of every chart component instead of
 * importing from `chart.js/auto` (which bundles every chart type, ~200KB extra).
 *
 * Only the components actually used are registered, keeping the chunk lean.
 */
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    ArcElement,
    Title,
    Tooltip,
    Legend,
    Filler,
} from "chart.js";

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    ArcElement,
    Title,
    Tooltip,
    Legend,
    Filler
);

export { ChartJS };
