import PropTypes from "prop-types";
import {
    Card,
    CardContent,
    CardHeader,
    Typography,
    Avatar,
} from "@mui/material";
import { red, green, orange } from "@mui/material/colors";
import { CANT_WALK, DEAD } from "../constants/animal-status";

function AnimalHealthCard(props) {
    const { animal } = props;
    const { name, current_health, life_span, status } = animal;

    let statusColor = green[500];

    if (status === CANT_WALK) {
        statusColor = orange[500];
    } else if (status === DEAD) {
        statusColor = red[500];
    }

    return (
        <Card
            sx={{
                borderLeft: `5px solid ${statusColor}`,
            }}
        >
            <CardHeader
                avatar={
                    <Avatar sx={{ bgcolor: red[500] }} aria-label={name}>
                        {name.charAt(0)}
                        {name.charAt(name.length - 1)}
                    </Avatar>
                }
                title={name}
                subheader={`Status: ${status}`}
            />
            <CardContent>
                <Typography>{`Health: ${current_health}/100`}</Typography>
                <Typography>{`Life Span: ${life_span} ${
                    life_span > 1 ? "Hrs" : "Hr"
                }`}</Typography>
            </CardContent>
        </Card>
    );
}

AnimalHealthCard.proptypes = {
    animal: PropTypes.shape({
        name: PropTypes.string,
        current_health: PropTypes.number,
        life_span: PropTypes.number,
        status: PropTypes.string,
    }).isRequired,
};

export default AnimalHealthCard;
