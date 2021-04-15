package uwb.licencjat.validator;

import javax.validation.Constraint;
import javax.validation.Payload;
import java.lang.annotation.Documented;
import java.lang.annotation.Retention;
import java.lang.annotation.Target;

import static java.lang.annotation.ElementType.*;
import static java.lang.annotation.RetentionPolicy.*;

@Documented
@Constraint(validatedBy = PasswordConstraintValidator.class)
@Target({ METHOD, FIELD, CONSTRUCTOR, PARAMETER, ANNOTATION_TYPE })
@Retention(RUNTIME)
public @interface PasswordValid {
    String message() default "Hasło musi zawierać od 8 do 30 znaków w tym: małą i wielką literę, cyfrę i znak " +
            "specialny";
    Class<?>[] groups() default {};
    Class<? extends Payload>[] payload() default {};
}
