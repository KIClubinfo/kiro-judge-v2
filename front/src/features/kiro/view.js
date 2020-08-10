import React from "react";
import {useSelector} from "react-redux";
import {selectCurrentView} from "./kiroSlice";
import {ScoreBoard} from "../leaderboard/view";
import {Dashboard} from "../dashboard/view";
import {NotFound} from "../404/view.jsx";
import {Index} from "../index/view";
import {Teams} from "../teams/view";
import style from "./view.module.css"

export function MainView() {
    return (
        <section className="main_view">
            <MainViewConditional />
        </section>
    )
}

function MainViewConditional() {
    const currentView = useSelector(selectCurrentView);

    switch (currentView) {
        case 'Dashboard':
            return <Dashboard />;
        case 'Leaderboard':
            return <ScoreBoard />;
        case 'Index':
            return <Index />
        case 'Teams':
            return <Teams />
        default:
            return <NotFound />;
    }
}