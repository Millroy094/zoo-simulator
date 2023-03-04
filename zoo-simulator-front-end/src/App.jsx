import moment from "moment/moment";
import { useState, useEffect, useRef } from "react";
import { Button, Container, Grid, Paper } from "@mui/material";
import apiCreateZoo from "./api/api-create-zoo";
import apiFeedZoo from "./api/api-feed-zoo";
import apiDeleteZoo from "./api/api-delete-zoo";
import apiIncrementHourAtZoo from "./api/api-increment-hour-at-zoo";

import { HTTP_CREATED, HTTP_OK } from "./constants/http-status";

function App() {
    const isMounted = useRef(true);
    const [animals, setAnimals] = useState([]);
    const [zooTime, setZooTime] = useState(null);

    useEffect(() => {
        const createZoo = async () => {
            const { data, status } = await apiCreateZoo();
            if (status === HTTP_CREATED) {
                const { animals: animalResponse, current_time } = data;
                setZooTime(moment(current_time, "YYYY-MM-DDTHH:mm:ss"));
                setAnimals(animalResponse);
            } else {
                throw new Error("There was trouble creating the zoo");
            }
        };
        if (isMounted.current) {
            try {
                createZoo();
            } catch (ex) {
                console.log(ex);
            }
        }
    }, []);

    const feedAnimals = async () => {
        const { data, status } = await apiFeedZoo();
        if (status === HTTP_OK) {
            const { animals: animalResponse, current_time } = data;
            setZooTime(moment(current_time, "YYYY-MM-DDTHH:mm:ss"));
            setAnimals(animalResponse);
        } else {
            throw new Error("There was trouble feeding the zoo");
        }
    };

    const incrementZooTimeByAnHour = async () => {
        const { data, status } = await apiIncrementHourAtZoo();
        if (status === HTTP_OK) {
            const { animals: animalResponse, current_time } = data;
            setZooTime(moment(current_time, "YYYY-MM-DDTHH:mm:ss"));
            setAnimals(animalResponse);
        } else {
            throw new Error("There was trouble incrementing hour at the zoo");
        }
    };

    const destroyZoo = async () => {
        const { data, status } = await apiDeleteZoo();
        if (status === HTTP_OK) {
            const { animals: animalResponse } = data;
            setZooTime(null);
            setAnimals(animalResponse);
        } else {
            throw new Error("There was trouble terminating the zoo");
        }
    };

    return (
        <Container>
            <Paper>
                <Grid container>
                    <Grid item xs={12}>
                        {zooTime && zooTime.format("DD MM YYYY HH:MM")}
                    </Grid>
                    <Grid container item xs={12}>
                        {animals.map((animal) => (
                            <Grid
                                key={animal.id}
                                item
                                xs={2}
                            >{`${animal.name} - ${animal.current_health}/100`}</Grid>
                        ))}
                    </Grid>
                    <Grid container item xs={12}>
                        <Button onClick={incrementZooTimeByAnHour}>
                            Add an Hour
                        </Button>
                        <Button onClick={feedAnimals}>feed zoo</Button>
                    </Grid>
                </Grid>
            </Paper>
        </Container>
    );
}

export default App;
